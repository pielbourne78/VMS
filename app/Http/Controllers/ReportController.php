<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $violations = \App\Models\Violation::query()
            ->with(['student'])
            ->orderByDesc('occurred_at')
            ->get();

        return view('admin.report', compact('violations'));
    }
}