<?php

namespace App\Http\Controllers;

use App\Services\DisciplineDashboardService;

class AdminController extends Controller
{
    public function index(DisciplineDashboardService $service)
    {
        $data = $service->forAdmin();

        return view('admin.dashboard', [
            'stats' => $data['stats'],
            'violations' => $data['violations'],
            'recent' => $data['recent'],
        ]);
    }
}