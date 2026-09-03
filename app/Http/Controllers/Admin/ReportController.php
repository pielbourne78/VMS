<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        // TEMPORARY: Dummy data so you can view and style the interface without the model
        $violations = collect([
            (object)[
                'id' => 1,
                'user' => (object)['name' => 'Gab Baltazar'],
                'violation_type' => 'Nagma-madjong',
                'status' => 'PENDING',
                'created_at' => now(),
            ],
            (object)[
                'id' => 2,
                'user' => (object)['name' => 'BOB'],
                'violation_type' => 'BULLYING',
                'status' => 'RESOLVED',
                'created_at' => now()->subDay(),
            ],
            (object)[
                'id' => 3,
                'user' => (object)['name' => 'Josh Agustin'],
                'violation_type' => 'VANDALISM',
                'status' => 'PENDING',
                'created_at' => now()->subDays(2),
            ],
        ]);

        return view('admin.report', compact('violations'));
    }
}