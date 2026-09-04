<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Violation;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalViolations' => Violation::count(),
            'activeCases' => Violation::where('status', 'approved')->count(),
            'pendingAlerts' => Violation::where('status', 'pending')->count(),
            'resolvedCases' => Violation::where('status', 'rejected')->count(),
            'notifications' => $this->adminNotifications(),
        ]);
    }

    public function violationMonitoring()
    {
        return view('admin.violations', [
            'students' => User::query()->where('is_admin', false)->orderBy('name')->get(),
            'recentViolations' => Violation::query()->with('student')->latest('violation_datetime')->latest()->get(),
            'violationTypes' => ['Cheating', 'Uniform', 'Bullying', 'Lost ID', 'Smoking/Vaping'],
            'locations' => ['Classroom', 'Laboratory', 'Canteen', 'Hallway', 'School Grounds'],
            'notifications' => $this->adminNotifications(),
        ]);
    }

    public function report()
    {
        return view('admin.report', [
            'violations' => Violation::query()->latest('violation_datetime')->latest()->get(),
            'notifications' => $this->adminNotifications(),
        ]);
    }

    private function adminNotifications()
    {
        return Violation::query()
            ->where('notification_alert', true)
            ->latest('violation_datetime')
            ->latest()
            ->get();
    }

    public function storeViolation(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'violation_type' => ['required', 'string', 'max:100'],
            'occurred_at' => ['required', 'date'],
            'description' => ['required', 'string', 'max:2000'],
            'location' => ['required', 'string', 'max:100'],
            'notification_alert' => ['nullable', 'boolean'],
            'student_notification' => ['nullable', 'boolean'],
        ]);

        $validated['notification_alert'] = $request->boolean('notification_alert');
        $validated['student_notification'] = $request->boolean('student_notification');
        $validated['status'] = 'pending';
        $student = User::findOrFail($validated['user_id']);
        $validated['violator_name'] = $student->name;
        $validated['student_id'] = $student->student_id;
        $validated['violation_datetime'] = $validated['occurred_at'];
        $validated['created_by'] = $request->user()->id;
        unset($validated['user_id'], $validated['occurred_at']);

        Violation::create($validated);

        return to_route('admin.violation.monitoring')->with('success', 'Violation recorded and marked as pending.');
    }
}