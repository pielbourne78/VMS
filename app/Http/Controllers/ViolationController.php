<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ViolationController extends Controller
{
    public function index()
    {
        return view('violations.index'); // Points to resources/views/violations/index.blade.php
    }
}