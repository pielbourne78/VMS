@extends('layouts.admin')

@section('title', 'Notifications')

@section('content')
    <div class="w-full space-y-6">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm font-bold uppercase tracking-[0.2em] text-red-700">Admin Center</p>
                <h2 class="mt-2 text-3xl font-black text-gray-900">ALL NOTIFICATIONS</h2>
            </div>
            <a href="{{ route('admin.notifications.index') }}"
                class="inline-flex items-center justify-center rounded-full border border-red-200 bg-red-50 px-4 py-2 text-sm font-bold text-red-700 transition hover:bg-red-100">
                Reset Filters
            </a>
        </div>

        <form method="GET" action="{{ route('admin.notifications.index') }}" class="rounded-2xl border border-gray-200 bg-gray-50 p-4 shadow-sm">
            <div class="grid gap-4 md:grid-cols-5">
                <div>
                    <label for="status" class="mb-2 block text-xs font-bold uppercase tracking-[0.15em] text-gray-600">Status</label>
                    <select id="status" name="status" class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-200">
                        <option value="all" {{ $filters['status'] === 'all' ? 'selected' : '' }}>All</option>
                        <option value="unread" {{ $filters['status'] === 'unread' ? 'selected' : '' }}>Unread</option>
                        <option value="read" {{ $filters['status'] === 'read' ? 'selected' : '' }}>Read</option>
                    </select>
                </div>

                <div>
                    <label for="type" class="mb-2 block text-xs font-bold uppercase tracking-[0.15em] text-gray-600">Type</label>
                    <select id="type" name="type" class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-200">
                        <option value="all" {{ $filters['type'] === 'all' ? 'selected' : '' }}>All Types</option>
                        <option value="student_quiz_submission" {{ $filters['type'] === 'student_quiz_submission' ? 'selected' : '' }}>Quiz submission</option>
                        <option value="violation_status" {{ $filters['type'] === 'violation_status' ? 'selected' : '' }}>Violation update</option>
                        <option value="appeal_decision" {{ $filters['type'] === 'appeal_decision' ? 'selected' : '' }}>Appeal decision</option>
                    </select>
                </div>

                <div>
                    <label for="from_date" class="mb-2 block text-xs font-bold uppercase tracking-[0.15em] text-gray-600">From Date &amp; Time</label>
                    <input id="from_date" type="datetime-local" name="from_date" value="{{ $filters['from_date'] ?? '' }}" class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-200">
                </div>

                <div>
                    <label for="to_date" class="mb-2 block text-xs font-bold uppercase tracking-[0.15em] text-gray-600">To Date &amp; Time</label>
                    <input id="to_date" type="datetime-local" name="to_date" value="{{ $filters['to_date'] ?? '' }}" class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-200">
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full rounded-xl bg-red-700 px-4 py-2.5 text-sm font-bold uppercase tracking-wide text-white transition hover:bg-red-800">
                        Apply
                    </button>
                </div>
            </div>
        </form>

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            @forelse ($notifications as $notification)
                @php($data = $notification->data ?? [])
                <div data-notification-id="{{ $notification->id }}" class="flex flex-col gap-3 border-b border-gray-200 p-4 md:flex-row md:items-center md:justify-between {{ is_null($notification->read_at) ? 'bg-red-50/60' : 'bg-white' }}">
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex rounded-full bg-red-100 px-2.5 py-1 text-[10px] font-black uppercase tracking-[0.18em] text-red-700">
                                {{ strtoupper(str_replace('_', ' ', $data['type'] ?? 'notification')) }}
                            </span>
                            @if (is_null($notification->read_at))
                                <span class="inline-flex rounded-full bg-amber-100 px-2 py-1 text-[10px] font-black uppercase tracking-[0.18em] text-amber-700">
                                    Unread
                                </span>
                            @else
                                <span class="inline-flex rounded-full bg-emerald-100 px-2 py-1 text-[10px] font-black uppercase tracking-[0.18em] text-emerald-700">
                                    Read
                                </span>
                            @endif
                        </div>

                        <div>
                            <p class="text-base font-black text-gray-900">{{ $data['title'] ?? 'Notification' }}</p>
                            <p class="mt-1 text-sm text-gray-600">{{ $data['message'] ?? 'You have a new update.' }}</p>
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
                                Open
                            </a>
                        @endif

                        @if (is_null($notification->read_at))
                            <button type="button" onclick="markAdminNotificationRead('{{ $notification->id }}', this.closest('[data-notification-id]'))" class="inline-flex items-center justify-center rounded-full border border-red-200 bg-white px-4 py-2 text-xs font-bold uppercase tracking-wide text-red-700 transition hover:bg-red-50">
                                Mark read
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-10 text-center text-gray-500">
                    <p class="text-lg font-bold">No notifications match your current filters.</p>
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
