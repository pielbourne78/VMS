@extends('layouts.admin')

@section('title', 'Appeal History')

@section('content')
    @php
        $statusFromNotification = function ($notification) {
            $data = $notification->data ?? [];
            $title = strtolower((string) ($data['title'] ?? ''));
            $message = strtolower((string) ($data['message'] ?? ''));

            if (str_contains($title, 'approved') || str_contains($message, 'approved')) {
                return 'approved';
            }

            if (str_contains($title, 'not approved') || str_contains($message, 'not approved')
                || str_contains($title, 'declined') || str_contains($message, 'declined')) {
                return 'declined';
            }

            if (str_contains($title, 'pending') || str_contains($message, 'pending')
                || str_contains($title, 'received') || str_contains($title, 'review')) {
                return 'pending';
            }

            return ($data['type'] ?? null) === 'appeal_decision' ? 'resolved' : 'pending';
        };

        $totalReports = $notifications->total() ?? 0;
        $pendingCount = $notifications->filter(fn ($notification) => $statusFromNotification($notification) === 'pending')->count();
        $approvedCount = $notifications->filter(fn ($notification) => $statusFromNotification($notification) === 'approved')->count();
        $declinedCount = $notifications->filter(fn ($notification) => $statusFromNotification($notification) === 'declined')->count();
        $unreadCount = $notifications->filter(fn ($notification) => is_null($notification->read_at))->count();
    @endphp

    <div class="w-full space-y-6">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm font-black uppercase tracking-[0.2em] text-red-700">Appeal center</p>
                <h2 class="mt-2 text-3xl font-black text-gray-900">Appeals history and review ledger</h2>
            </div>
            <a href="{{ route('admin.appeal.reports') }}"
                class="inline-flex items-center justify-center rounded-full border border-red-200 bg-red-50 px-4 py-2 text-sm font-bold text-red-700 transition hover:bg-red-100">
                Reset filters
            </a>
        </div>

        <div class="grid gap-4 md:grid-cols-5">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400">Total records</p>
                <p class="mt-2 text-3xl font-black text-gray-900">{{ $totalReports }}</p>
            </div>
            <div class="rounded-2xl border border-yellow-200 bg-yellow-50 p-5 shadow-sm">
                <p class="text-[10px] uppercase tracking-[0.2em] text-yellow-700">Pending</p>
                <p class="mt-2 text-3xl font-black text-yellow-600">{{ $pendingCount }}</p>
            </div>
            <div class="rounded-2xl border border-green-200 bg-green-50 p-5 shadow-sm">
                <p class="text-[10px] uppercase tracking-[0.2em] text-green-700">Approved</p>
                <p class="mt-2 text-3xl font-black text-green-600">{{ $approvedCount }}</p>
            </div>
            <div class="rounded-2xl border border-red-200 bg-red-50 p-5 shadow-sm">
                <p class="text-[10px] uppercase tracking-[0.2em] text-red-700">Disapproved</p>
                <p class="mt-2 text-3xl font-black text-red-600">{{ $declinedCount }}</p>
            </div>
            <div class="rounded-2xl border border-sky-200 bg-sky-50 p-5 shadow-sm">
                <p class="text-[10px] uppercase tracking-[0.2em] text-sky-700">Unread</p>
                <p class="mt-2 text-3xl font-black text-sky-600">{{ $unreadCount }}</p>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.appeal.reports') }}" class="rounded-2xl border border-gray-200 bg-gray-50 p-4 shadow-sm">
            <div class="grid gap-4 md:grid-cols-4">
                <div>
                    <label for="status" class="mb-2 block text-xs font-bold uppercase tracking-[0.15em] text-gray-600">Status</label>
                    <select id="status" name="status" class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-200">
                        <option value="all" {{ ($filters['status'] ?? 'all') === 'all' ? 'selected' : '' }}>All</option>
                        <option value="pending" {{ ($filters['status'] ?? 'all') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ ($filters['status'] ?? 'all') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="declined" {{ ($filters['status'] ?? 'all') === 'declined' ? 'selected' : '' }}>Disapproved</option>
                        <option value="unread" {{ ($filters['status'] ?? 'all') === 'unread' ? 'selected' : '' }}>Unread</option>
                        <option value="read" {{ ($filters['status'] ?? 'all') === 'read' ? 'selected' : '' }}>Read</option>
                    </select>
                </div>

                <div>
                    <label for="type" class="mb-2 block text-xs font-bold uppercase tracking-[0.15em] text-gray-600">Type</label>
                    <select id="type" name="type" class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-200">
                        <option value="all" {{ ($filters['type'] ?? 'all') === 'all' ? 'selected' : '' }}>All Records</option>
                        <option value="student_quiz_submission" {{ ($filters['type'] ?? 'all') === 'student_quiz_submission' ? 'selected' : '' }}>Reasoning submitted</option>
                        <option value="appeal_decision" {{ ($filters['type'] ?? 'all') === 'appeal_decision' ? 'selected' : '' }}>Decision outcome</option>
                    </select>
                </div>

                <div>
                    <label for="from_date" class="mb-2 block text-xs font-bold uppercase tracking-[0.15em] text-gray-600">From date</label>
                    <input id="from_date" type="datetime-local" name="from_date" value="{{ $filters['from_date'] ?? '' }}" class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-200">
                </div>

                <div>
                    <label for="to_date" class="mb-2 block text-xs font-bold uppercase tracking-[0.15em] text-gray-600">To date</label>
                    <input id="to_date" type="datetime-local" name="to_date" value="{{ $filters['to_date'] ?? '' }}" class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-200">
                </div>
            </div>

            <div class="mt-4 flex justify-end">
                <button type="submit" class="rounded-xl bg-red-700 px-4 py-2.5 text-sm font-bold uppercase tracking-wide text-white transition hover:bg-red-800">
                    Apply filters
                </button>
            </div>
        </form>

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            @forelse ($notifications as $notification)
                @php
                    $data = $notification->data ?? [];
                    $type = $data['type'] ?? 'appeal_report';
                    $typeLabel = strtoupper(str_replace('_', ' ', $type));
                    $recordStatus = $statusFromNotification($notification);
                    $statusText = match ($recordStatus) {
                        'approved' => 'Approved',
                        'declined' => 'Disapproved',
                        'pending' => 'Pending review',
                        'resolved' => 'Resolved',
                        default => 'Pending review',
                    };
                    $statusBadgeClass = match ($recordStatus) {
                        'approved' => 'bg-emerald-100 text-emerald-700',
                        'declined' => 'bg-red-100 text-red-700',
                        'pending' => 'bg-amber-100 text-amber-700',
                        'resolved' => 'bg-sky-100 text-sky-700',
                        default => 'bg-amber-100 text-amber-700',
                    };
                    $readBadgeClass = is_null($notification->read_at) ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700';
                @endphp

                <div data-notification-id="{{ $notification->id }}" class="flex flex-col gap-4 border-b border-gray-200 p-4 md:flex-row md:items-center md:justify-between {{ is_null($notification->read_at) ? 'bg-red-50/70' : 'bg-white' }}">
                    <div class="space-y-3">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="inline-flex rounded-full bg-red-100 px-2.5 py-1 text-[10px] font-black uppercase tracking-[0.18em] text-red-700">
                                {{ $typeLabel }}
                            </span>
                            <span class="inline-flex rounded-full {{ $statusBadgeClass }} px-2 py-1 text-[10px] font-black uppercase tracking-[0.18em]">
                                {{ $statusText }}
                            </span>
                            <span class="inline-flex rounded-full {{ $readBadgeClass }} px-2 py-1 text-[10px] font-black uppercase tracking-[0.18em]">
                                {{ is_null($notification->read_at) ? 'Unread' : 'Read' }}
                            </span>
                        </div>

                        <div>
                            <p class="text-base font-black text-gray-900">{{ $data['title'] ?? 'Appeal case record' }}</p>
                            <p class="mt-1 text-sm text-gray-600">{{ $data['message'] ?? 'No summary available for this record.' }}</p>
                        </div>

                        <div class="flex flex-wrap items-center gap-2 text-xs font-medium text-gray-500">
                            <span>{{ $notification->created_at?->format('M d, Y') ?? 'N/A' }}</span>
                            <span>•</span>
                            <span>{{ $notification->created_at?->format('h:i A') ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 md:flex-col md:items-end">
                        @if (! empty($data['url']))
                            <a href="{{ $data['url'] }}" class="inline-flex items-center justify-center rounded-full bg-gray-900 px-4 py-2 text-xs font-bold uppercase tracking-wide text-white transition hover:bg-gray-700">
                                View case
                            </a>
                        @endif

                        <a href="{{ route('admin.appeals') }}" class="inline-flex items-center justify-center rounded-full border border-gray-300 bg-white px-4 py-2 text-xs font-bold uppercase tracking-wide text-gray-700 transition hover:bg-gray-100">
                            Review queue
                        </a>

                        <button type="button" data-toggle-read-button onclick="markAdminNotificationRead('{{ $notification->id }}', this)" class="inline-flex items-center justify-center rounded-full border border-red-200 bg-white px-4 py-2 text-xs font-bold uppercase tracking-wide text-red-700 transition hover:bg-red-50">
                            {{ is_null($notification->read_at) ? 'Mark read' : 'Mark unread' }}
                        </button>

                        <button type="button" onclick="deleteAdminNotification('{{ $notification->id }}', this.closest('[data-notification-id]'))" class="inline-flex items-center justify-center rounded-full border border-red-200 bg-red-50 px-4 py-2 text-xs font-bold uppercase tracking-wide text-red-700 transition hover:bg-red-100">
                            Delete
                        </button>
                    </div>
                </div>
            @empty
                <div class="p-10 text-center text-gray-500">
                    <p class="text-lg font-bold">No appeal or reasoning records match the current history filters.</p>
                </div>
            @endforelse
        </div>

        @if ($notifications->hasPages())
            <div class="mt-4 flex justify-center">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
@endsection
