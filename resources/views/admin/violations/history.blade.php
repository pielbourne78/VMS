@extends('layouts.admin')

@section('title', 'Student Violation History')

@section('content')
    @php
        $allViolations = $student->violations()->orderByDesc('occurred_at')->get();
        $totalViolations = $allViolations->count();
        $resolvedCount = $allViolations->where('status', 'resolved')->count();
        $pendingCount = $allViolations->where('status', 'pending')->count();
        $activeCount = $allViolations->where('status', 'consequence_applied')->count();
    @endphp

    <div class="mb-6 flex items-center justify-between gap-4">
        <div>
            <p class="text-xs font-black uppercase tracking-[0.2em] text-red-700">Student Record</p>
            <h2 class="mt-2 text-3xl font-black text-gray-900">Violation History</h2>
        </div>
        <a href="{{ route('admin.violations.index') }}"
            class="rounded-full bg-gray-900 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-gray-700">
            Back to list
        </a>
    </div>

    <div class="mb-8 grid gap-4 md:grid-cols-4">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-black uppercase tracking-[0.18em] text-gray-500">Student</p>
            <h3 class="mt-3 text-xl font-black text-gray-900">{{ $student->full_name ?? $student->name }}</h3>
            <p class="mt-1 text-sm text-gray-600">{{ $student->student_id ?? 'N/A' }}</p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-black uppercase tracking-[0.18em] text-gray-500">Course</p>
            <p class="mt-3 text-lg font-bold text-gray-900">{{ $student->course ?? 'Not set' }}</p>
            <p class="mt-1 text-sm text-gray-600">Section {{ $student->section ?? 'N/A' }}</p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-black uppercase tracking-[0.18em] text-gray-500">Status counts</p>
            <div class="mt-3 space-y-2 text-sm font-semibold text-gray-700">
                <div class="flex items-center justify-between"><span>Pending</span><span>{{ $pendingCount }}</span></div>
                <div class="flex items-center justify-between"><span>Resolved</span><span>{{ $resolvedCount }}</span></div>
                <div class="flex items-center justify-between"><span>Active</span><span>{{ $activeCount }}</span></div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-black uppercase tracking-[0.18em] text-gray-500">Total Cases</p>
            <p class="mt-3 text-3xl font-black text-red-700">{{ $totalViolations }}</p>
            <p class="mt-1 text-sm text-gray-600">Violation records</p>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white shadow-lg overflow-hidden">
        <div class="max-h-[760px] overflow-y-auto">
            <table class="w-full text-left text-sm">
                <thead class="grc-red text-white uppercase text-xs font-bold sticky top-0 z-10">
                    <tr>
                        <th class="px-5 py-3">Case ID</th>
                        <th class="px-5 py-3">Type</th>
                        <th class="px-5 py-3">Date & time</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Description</th>
                        <th class="px-5 py-3">Issued by</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($violations as $violation)
                        @php
                            $status = strtolower((string) ($violation->status ?? 'pending'));
                            $statusClass = match ($status) {
                                'resolved' => 'bg-green-100 text-green-700',
                                'consequence_applied' => 'bg-red-100 text-red-700',
                                'dismissed' => 'bg-gray-200 text-gray-700',
                                default => 'bg-yellow-100 text-yellow-700',
                            };
                        @endphp
                        <tr class="align-top hover:bg-gray-50">
                            <td class="px-5 py-4 font-black text-red-700">{{ $violation->violation_code ?? 'N/A' }}</td>
                            <td class="px-5 py-4 font-semibold text-gray-900">{{ $violation->violation_type ?? 'N/A' }}</td>
                            <td class="px-5 py-4 text-gray-700">
                                {{ $violation->occurred_at?->format('M d, Y') ?? 'N/A' }}<br>
                                <span class="font-medium">{{ $violation->occurred_at?->format('h:i A') ?? '' }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <span
                                    class="inline-flex rounded-full px-3 py-1 text-[11px] font-black uppercase tracking-wide {{ $statusClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-gray-700">
                                <div class="space-y-2">
                                    <p>{{ $violation->description ?? 'No description provided.' }}</p>
                                    @if (!empty($violation->resolution_notes))
                                        <p class="text-xs text-gray-500"><span class="font-bold">Resolution:</span>
                                            {{ $violation->resolution_notes }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-4 text-gray-700">
                                {{ $violation->issuedBy->full_name ?? $violation->issuedBy->name ?? 'Admin' }}
                                @if ($violation->resolved_by)
                                    <div class="mt-2 text-xs text-gray-500">
                                        Resolved by:
                                        {{ $violation->resolvedBy?->full_name ?? $violation->resolvedBy?->name ?? 'Admin' }}
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-gray-500">No violation records found for this
                                student.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $violations->links() }}
    </div>
@endsection