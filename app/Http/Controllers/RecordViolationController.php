<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Violation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecordViolationController extends Controller
{
    public function create()
    {
        $students = User::where('role', 'student')
            ->orderBy('student_id')
            ->get();

        $violationTypes = [
            'Late',
            'Uniform Violation',
            'Misconduct',
            'Absence',
            'Disrespect',
            'Others'
        ];

        $locations = [
            'Building A',
            'Building B',
            'Canteen',
            'Library',
            'Parking Area',
            'Gymnasium',
            'Classroom'
        ];

        return view('admin.violation-monitoring', compact('students', 'violationTypes', 'locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'              => 'required|exists:users,id',
            'violation_type'       => 'required|string',
            'occurred_at'          => 'required|date',
            'location'             => 'required|string',
            'description'          => 'required|string',
            'notification_alert'   => 'nullable',
            'student_notification' => 'nullable',
        ]);

        Violation::create([
            'violation_code'  => 'V-' . strtoupper(uniqid()),
            'user_id'         => $validated['user_id'],
            'issued_by'       => Auth::id(),
            'violation_type'  => $validated['violation_type'],
            'description'     => $validated['location'] . ' — ' . $validated['description'],
            'occurred_at'     => $validated['occurred_at'],
            'status'          => 'pending',
        ]);

        return redirect()
            ->route('admin.violation.monitoring')
            ->with('success', 'Violation recorded successfully!');
    }
}