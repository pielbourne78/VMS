<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Violation;

class ViolationController extends Controller
{
    public function reportIndex()
    {
        $user = auth()->user();

        // Fetch violations belonging to the logged-in student using student_id
        $violations = Violation::where('student_id', $user->id)->get();

        return view('report', compact('violations'));
    }
}