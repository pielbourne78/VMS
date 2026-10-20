@extends('layouts.admin')

@section('title', 'Operational Report')

@section('content')
    @php
        $report = $report ?? [
            'total_cases' => 0,
            'active_cases' => 0,
            'pending_actions' => 0,
            'resolved_this_week' => 0,
            'escalated_cases' => 0,
            'major_cases' => 0,
            'minor_cases' => 0,
            'student_response_rate' => 0,
            'resolution_window' => 0,
        ];
    @endphp

    <div class="space-y-8">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-red-600 font-bold">Operations</p>
                <h2 class="text-3xl font-black text-gray-900">Weekly discipline report</h2>
            </div>
            <div class="rounded-full bg-red-100 px-4 py-2 text-sm font-bold text-red-700">
                {{ now()->format('M d, Y') }}
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400">Total cases</p>
                <p class="mt-2 text-3xl font-black text-gray-900">{{ $report['total_cases'] }}</p>
            </div>
            <div class="bg-orange-50 border border-orange-200 rounded-2xl p-5 shadow-sm">
                <p class="text-[10px] uppercase tracking-[0.2em] text-orange-700">Active</p>
                <p class="mt-2 text-3xl font-black text-orange-600">{{ $report['active_cases'] }}</p>
            </div>
            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5 shadow-sm">
                <p class="text-[10px] uppercase tracking-[0.2em] text-blue-700">Pending actions</p>
                <p class="mt-2 text-3xl font-black text-blue-600">{{ $report['pending_actions'] }}</p>
            </div>
            <div class="bg-green-50 border border-green-200 rounded-2xl p-5 shadow-sm">
                <p class="text-[10px] uppercase tracking-[0.2em] text-green-700">Resolved this week</p>
                <p class="mt-2 text-3xl font-black text-green-600">{{ $report['resolved_this_week'] }}</p>
            </div>
            <div class="bg-purple-50 border border-purple-200 rounded-2xl p-5 shadow-sm">
                <p class="text-[10px] uppercase tracking-[0.2em] text-purple-700">Escalated</p>
                <p class="mt-2 text-3xl font-black text-purple-600">{{ $report['escalated_cases'] }}</p>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400">Major cases</p>
                <p class="mt-2 text-3xl font-black text-red-600">{{ $report['major_cases'] }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400">Minor cases</p>
                <p class="mt-2 text-3xl font-black text-yellow-600">{{ $report['minor_cases'] }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400">Response rate</p>
                <p class="mt-2 text-3xl font-black text-gray-900">{{ $report['student_response_rate'] }}%</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-xl border border-gray-200 overflow-hidden">
            <div class="border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-black text-gray-900">Recent case records</h3>
                <div class="text-sm font-bold text-gray-500">Avg. resolution window: {{ $report['resolution_window'] }} days</div>
            </div>

            <div class="divide-y divide-gray-200">
                @forelse($violations ?? [] as $violation)
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 px-6 py-4 items-center">
                        <div>
                            <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400">Student</p>
                            <p class="mt-1 font-bold text-gray-900">{{ $violation->student->full_name ?? $violation->student->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400">Violation</p>
                            <p class="mt-1 font-bold text-gray-800">{{ $violation->violation_type ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400">Status</p>
                            <span class="mt-1 inline-flex rounded-full px-2.5 py-1 text-[11px] font-bold uppercase tracking-wide
                                {{ $violation->status === 'resolved' ? 'bg-green-100 text-green-700' : ($violation->review_level === 'escalated' ? 'bg-purple-100 text-purple-700' : 'bg-yellow-100 text-yellow-700') }}">
                                {{ $violation->review_level === 'escalated' ? 'ESCALATED' : ($violation->status === 'consequence_applied' ? 'PENDING' : ($violation->status === 'pending' ? 'ACTIVE CASE' : strtoupper($violation->status))) }}
                            </span>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400">Case owner</p>
                            <p class="mt-1 font-bold text-gray-800">{{ $violation->caseOwner?->full_name ?? $violation->recordedBy?->full_name ?? 'Unassigned' }}</p>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-16 text-center text-gray-400 font-bold uppercase tracking-wider text-sm">
                        No violation reports found.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection