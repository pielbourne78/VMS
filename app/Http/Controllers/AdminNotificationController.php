<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class AdminNotificationController extends Controller
{
    public function index(Request $request): View
    {
        $query = $request->user()->notifications()->latest();

        if ($request->filled('status')) {
            $status = $request->input('status');

            if ($status === 'unread') {
                $query->whereNull('read_at');
            } elseif ($status === 'read') {
                $query->whereNotNull('read_at');
            }
        }

        if ($request->filled('type') && $request->input('type') !== 'all') {
            $query->where('data->type', $request->input('type'));
        }

        if ($request->filled('from_date')) {
            $query->where('created_at', '>=', Carbon::parse($request->input('from_date'))->toDateTimeString());
        }

        if ($request->filled('to_date')) {
            $query->where('created_at', '<=', Carbon::parse($request->input('to_date'))->toDateTimeString());
        }

        $notifications = $query->paginate(15)->appends($request->query());

        return view('admin.notifications', [
            'notifications' => $notifications,
            'filters' => [
                'status' => $request->input('status', 'all'),
                'type' => $request->input('type', 'all'),
                'from_date' => $request->input('from_date'),
                'to_date' => $request->input('to_date'),
            ],
        ]);
    }
}
