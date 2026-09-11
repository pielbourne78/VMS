<?php

namespace App\Http\Controllers;

use App\Models\Violation;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ConsequenceController extends Controller
{
    /**
     * Display pending violations awaiting consequence approval.
     */
    public function index()
    {
        $violations = Violation::with('user')
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('admin.apply-consequences', compact('violations'));
    }

    /**
     * Approve a violation, marking it as resolved.
     */
    public function approve(Violation $violation): RedirectResponse
    {
        $violation->update(['status' => 'resolved']);

        return redirect()
            ->route('admin.consequences')
            ->with('success', 'Violation has been approved and marked as resolved.');
    }
}