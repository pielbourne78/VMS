<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class AdminNotificationController extends Controller
{
    public function index(Request $request): View
    {
        $query = $this->buildNotificationQuery($request, $request->user()->notifications()->latest());
        $notifications = $query->paginate(15)->appends($request->query());

        return view('admin.notifications', [
            'notifications' => $notifications,
            'filters' => $this->buildFilterSummary($request),
        ]);
    }

    public function appealReports(Request $request): View
    {
        $query = $this->buildNotificationQuery($request, $request->user()
            ->notifications()
            ->whereIn('data->type', ['student_quiz_submission', 'appeal_decision'])
            ->latest());

        $allNotifications = $query->get();

        if ($allNotifications->isEmpty()) {
            $notifications = $this->mockAppealNotifications($request);
            $allNotifications = collect($notifications->items());
        } else {
            $notifications = $this->paginateNotifications($allNotifications, $request);
        }

        return view('admin.appeal-reports', [
            'notifications' => $notifications,
            'reportSummary' => $this->buildAppealReportSummary($allNotifications),
            'filters' => $this->buildFilterSummary($request),
        ]);
    }

    protected function buildNotificationQuery(Request $request, $query)
    {
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

        return $query;
    }

    protected function buildFilterSummary(Request $request): array
    {
        return [
            'status' => $request->input('status', 'all'),
            'type' => $request->input('type', 'all'),
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date'),
        ];
    }

    protected function paginateNotifications($notifications, Request $request): LengthAwarePaginator
    {
        $page = max(1, (int) $request->input('page', 1));
        $perPage = 15;
        $collection = collect($notifications);

        return new LengthAwarePaginator(
            $collection->forPage($page, $perPage)->values(),
            $collection->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    }

    protected function buildAppealReportSummary($notifications): array
    {
        $items = collect($notifications);

        return [
            'total' => $items->count(),
            'pending' => $items->filter(fn ($notification) => $this->notificationStatus($notification) === 'pending')->count(),
            'approved' => $items->filter(fn ($notification) => $this->notificationStatus($notification) === 'approved')->count(),
            'declined' => $items->filter(fn ($notification) => $this->notificationStatus($notification) === 'declined')->count(),
            'unread' => $items->filter(fn ($notification) => is_null($notification->read_at))->count(),
        ];
    }

    protected function notificationStatus($notification): string
    {
        $data = $notification->data ?? [];
        $title = strtolower((string) ($data['title'] ?? ''));
        $message = strtolower((string) ($data['message'] ?? ''));

        if (str_contains($title, 'not approved') || str_contains($message, 'not approved')
            || str_contains($title, 'declined') || str_contains($message, 'declined')) {
            return 'declined';
        }

        if (str_contains($title, 'approved') || str_contains($message, 'approved')) {
            return 'approved';
        }

        if (str_contains($title, 'pending') || str_contains($message, 'pending')
            || str_contains($title, 'received') || str_contains($title, 'review')) {
            return 'pending';
        }

        return ($data['type'] ?? null) === 'appeal_decision' ? 'resolved' : 'pending';
    }

    protected function mockAppealNotifications(Request $request): LengthAwarePaginator
    {
        $mockRecords = [
            [
                'id' => 1001,
                'read_at' => null,
                'created_at' => now()->subHours(3),
                'data' => [
                    'title' => 'Quiz submission received',
                    'message' => 'Angel Villafranca submitted a penalty quiz for review.',
                    'url' => route('admin.appeals'),
                    'type' => 'student_quiz_submission',
                ],
            ],
            [
                'id' => 1002,
                'read_at' => now()->subHours(12),
                'created_at' => now()->subDay(),
                'data' => [
                    'title' => 'Quiz submission received',
                    'message' => 'Rebecca Santos submitted a penalty quiz for review.',
                    'url' => route('admin.appeals'),
                    'type' => 'student_quiz_submission',
                ],
            ],
            [
                'id' => 1003,
                'read_at' => now()->subDays(2),
                'created_at' => now()->subDays(2),
                'data' => [
                    'title' => 'Reasoning Approved',
                    'message' => 'Your reasoning was approved and your violation case has been resolved.',
                    'url' => route('report'),
                    'type' => 'appeal_decision',
                ],
            ],
            [
                'id' => 1004,
                'read_at' => null,
                'created_at' => now()->subDays(4),
                'data' => [
                    'title' => 'Reasoning Review Pending',
                    'message' => 'Your reasoning was not approved. The case remains active for further review.',
                    'url' => route('report'),
                    'type' => 'appeal_decision',
                ],
            ],
        ];

        foreach ($request->session()->get('mock_appeal_decisions', []) as $id => $decision) {
            $mockRecords[] = [
                'id' => 3000 + (int) $id,
                'read_at' => null,
                'created_at' => $decision['reviewed_at'] ?? now(),
                'data' => [
                    'title' => ! empty($decision['admin_approved']) ? 'Reasoning Approved' : 'Reasoning Review Pending',
                    'message' => ! empty($decision['admin_approved'])
                        ? 'Your reasoning was approved and your violation case has been resolved.'
                        : 'Your reasoning was not approved. The case remains active for further review.',
                    'url' => route('admin.appeals'),
                    'type' => 'appeal_decision',
                ],
            ];
        }

        $statusFilter = $request->input('status', 'all');
        $typeFilter = $request->input('type', 'all');
        $fromDate = $request->filled('from_date') ? Carbon::parse($request->input('from_date'))->startOfMinute() : null;
        $toDate = $request->filled('to_date') ? Carbon::parse($request->input('to_date'))->endOfDay() : null;

        $filtered = collect($mockRecords)->filter(function (array $record) use ($statusFilter, $typeFilter, $fromDate, $toDate) {
            $data = $record['data'];
            $title = strtolower((string) ($data['title'] ?? ''));
            $message = strtolower((string) ($data['message'] ?? ''));
            $createdAt = Carbon::parse($record['created_at']);

            if ($typeFilter !== 'all' && ($data['type'] ?? null) !== $typeFilter) {
                return false;
            }

            if ($statusFilter === 'unread' && ! is_null($record['read_at'])) {
                return false;
            }

            if ($statusFilter === 'read' && is_null($record['read_at'])) {
                return false;
            }

            if ($statusFilter === 'pending') {
                $matches = str_contains($title, 'pending')
                    || str_contains($title, 'received')
                    || str_contains($title, 'review')
                    || str_contains($message, 'pending')
                    || str_contains($message, 'review');

                if ($data['type'] === 'appeal_decision' && ! $matches) {
                    return false;
                }

                if (! $matches && ($data['type'] ?? null) !== 'student_quiz_submission') {
                    return false;
                }
            }

            if ($statusFilter === 'approved' && ! (str_contains($title, 'approved') || str_contains($message, 'approved'))) {
                return false;
            }

            if ($statusFilter === 'declined' && ! (str_contains($title, 'pending') || str_contains($message, 'not approved') || str_contains($message, 'remain') || str_contains($message, 'review'))) {
                return false;
            }

            if ($fromDate && $createdAt->lt($fromDate)) {
                return false;
            }

            if ($toDate && $createdAt->gt($toDate)) {
                return false;
            }

            return true;
        })->values()->all();

        $page = max(1, (int) $request->input('page', 1));
        $perPage = 15;
        $items = collect($filtered)->map(function (array $record) {
            $notification = new \stdClass();
            $notification->id = $record['id'];
            $notification->read_at = $record['read_at'];
            $notification->created_at = $record['created_at'];
            $notification->data = $record['data'];

            return $notification;
        });

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $items->forPage($page, $perPage)->values(),
            $items->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    }
}
