@extends('layouts.admin')

@section('title', 'Violation Monitoring')

@php
    $filtering = request()->filled('status')
        || request()->filled('course')
        || request()->filled('section')
        || request()->filled('year_level');

    $summary = $summary ?? [
        'total' => 0,
        'active' => 0,
        'pending' => 0,
        'resolved' => 0,
        'dismissed' => 0,
        'major' => 0,
        'minor' => 0,
        'escalated' => 0,
    ];
@endphp
@section('content')
<div class="h-[calc(100vh-110px)] flex flex-col">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-4 flex-shrink-0">
        <h2 class="text-2xl font-black text-gray-900">Admin Violation Monitoring & Tracking</h2>
        <a href="{{ route('admin.dashboard') }}"
           class="grc-red text-white rounded-full px-4 py-2 text-sm font-bold shadow">
            Home
        </a>
    </div>

    {{-- Main content (no page scroll) --}}
    <div class="flex-1 flex gap-4 min-h-0">

        {{-- ===================== LEFT: TABLE ===================== --}}
        <div class="flex-1 min-w-0 flex flex-col bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
            
            {{-- Scrollable table only --}}
            <div class="flex-1 overflow-y-auto overflow-x-auto admin-scrollable-table">
                <table class="w-full text-left text-sm min-w-[900px]">
                    <thead class="grc-red text-white uppercase text-xs font-bold sticky top-0 z-10">
                        <tr>
                            @if(($mode ?? 'violations') === 'students')
                                <th class="px-4 py-3">Student ID</th>
                                <th class="px-4 py-3">Student Name</th>
                                <th class="px-4 py-3">Course</th>
                                <th class="px-4 py-3">Total Violations</th>
                                <th class="px-4 py-3 text-right">Action</th>
                            @else
                                <th class="px-4 py-3">ID</th>
                                <th class="px-4 py-3">Student Name</th>
                                <th class="px-4 py-3">Violation Type</th>
                                <th class="px-4 py-3">Date & Time</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3 text-right">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @if(($mode ?? 'violations') === 'students')
                            @forelse ($students as $student)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 font-bold text-red-700">{{ $student->student_id ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 font-semibold">{{ $student->full_name ?? $student->name }}</td>
                                    <td class="px-4 py-3">{{ $student->course ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 font-bold">{{ $student->violations_count }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('admin.violations.history', $student->id) }}"
                                           class="inline-flex items-center justify-center bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-3 py-1.5 rounded-full transition whitespace-nowrap">
                                            View History
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-12 text-center text-gray-400">No students with violations found.</td>
                                </tr>
                            @endforelse
                        @else
                            @forelse ($violations as $violation)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 font-bold text-red-700">{{ $violation->violation_code }}</td>
                                    <td class="px-4 py-3 font-semibold">
                                        {{ $violation->student->full_name ?? $violation->student->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 py-3">{{ $violation->violation_type }}</td>
                                    <td class="px-4 py-3 font-bold">
                                        {{ $violation->occurred_at?->format('M d, Y h:i A') ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wide
                                            {{ $violation->status === 'resolved' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                            {{ $violation->status === 'consequence_applied' ? 'Pending' : ucfirst($violation->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        @if ($violation->student)
                                            <a href="{{ route('admin.violations.history', $violation->student->id) }}"
                                               class="inline-flex items-center justify-center bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-3 py-1.5 rounded-full transition whitespace-nowrap">
                                                View History
                                            </a>
                                        @else
                                            <span class="text-xs text-gray-400 font-bold">No Student</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-12 text-center text-gray-400">No violations recorded.</td>
                                </tr>
                            @endforelse
                        @endif
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-4 py-2.5 border-t bg-gray-50 flex-shrink-0">
                @if(($mode ?? '') === 'students')
                    {{ $students->links() }}
                @else
                    {{ $violations->links() }}
                @endif
            </div>
        </div>

        {{-- ===================== RIGHT: COMPACT FILTERS ===================== --}}
        <div class="w-64 flex-shrink-0">
            <form method="GET" action="{{ route('admin.violations.index') }}"
                  class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm h-full overflow-y-auto">

                <h3 class="text-sm font-black text-gray-900 mb-3">Filters</h3>

                {{-- Search --}}
                <div class="mb-3">
                    <label class="block text-[11px] font-bold text-gray-500 mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Name or ID..."
                           class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:border-red-500">
                </div>

                {{-- Course --}}
                <div class="mb-3">
                    <label class="block text-[11px] font-bold text-gray-500 mb-1">Course</label>
                    <select name="course" class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:border-red-500">
                        <option value="">All Course</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course }}" {{ request('course') === $course ? 'selected' : '' }}>{{ $course }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Section + Year Level side by side --}}
                <div class="grid grid-cols-2 gap-2 mb-3">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 mb-1">Section</label>
                        <select name="section" class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:border-red-500">
                            <option value="">All</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section }}" {{ request('section') === $section ? 'selected' : '' }}>{{ $section }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 mb-1">Year</label>
                        <select name="year_level" class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:border-red-500">
                            <option value="">All</option>
                            @foreach ($yearLevels as $yearLevel)
                                <option value="{{ $yearLevel }}" {{ request('year_level') === $yearLevel ? 'selected' : '' }}>{{ $yearLevel }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Status --}}
                <div class="mb-3">
                    <label class="block text-[11px] font-bold text-gray-500 mb-1">Status</label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:border-red-500">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                    </select>
                </div>

                {{-- Sort + Order side by side --}}
                <div class="grid grid-cols-2 gap-2 mb-3">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 mb-1">Sort by</label>
                        <select name="sort_by" class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:border-red-500">
                            <option value="occurred_at" {{ request('sort_by') === 'occurred_at' ? 'selected' : '' }}>Date</option>
                            <option value="violation_type" {{ request('sort_by') === 'violation_type' ? 'selected' : '' }}>Type</option>
                            <option value="status" {{ request('sort_by') === 'status' ? 'selected' : '' }}>Status</option>
                            <option value="violation_code" {{ request('sort_by') === 'violation_code' ? 'selected' : '' }}>Code</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 mb-1">Order</label>
                        <select name="sort_dir" class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:border-red-500">
                            <option value="desc" {{ request('sort_dir', 'desc') === 'desc' ? 'selected' : '' }}>Desc</option>
                            <option value="asc" {{ request('sort_dir') === 'asc' ? 'selected' : '' }}>Asc</option>
                        </select>
                    </div>
                </div>

                {{-- Date range --}}
                <div class="grid grid-cols-2 gap-2 mb-4">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 mb-1">From</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                               class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:border-red-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 mb-1">To</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                               class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:border-red-500">
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="space-y-2">
                    <button type="submit"
                            class="w-full bg-red-600 hover:bg-red-700 text-white rounded-lg py-2 text-sm font-bold transition">
                        Apply Filters
                    </button>
                    <a href="{{ route('admin.violations.index') }}"
                       class="block w-full border border-gray-300 text-gray-700 rounded-lg py-2 text-sm font-bold text-center hover:bg-gray-50 transition">
                        Clear
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .admin-scrollable-table::-webkit-scrollbar {
        width: 7px;
        height: 7px;
    }
    .admin-scrollable-table::-webkit-scrollbar-track {
        background: #f3f4f6;
    }
    .admin-scrollable-table::-webkit-scrollbar-thumb {
        background: #c4c4c4;
        border-radius: 4px;
    }
</style>
    <style>
        .admin-scrollable-table::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        .admin-scrollable-table::-webkit-scrollbar-track {
            background: #f3f4f6;
        }

        .admin-scrollable-table::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 9999px;
            border: 2px solid #f3f4f6;
        }

        .admin-scrollable-table::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }
    </style>
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('violationFilterForm');
            if (!form) return;

            const searchInput = document.getElementById('searchInput');
            const selects = Array.from(form.querySelectorAll('select'));

            const submitForm = () => {
                form.requestSubmit();
            };

            if (searchInput) {
                let debounceTimer;
                searchInput.addEventListener('input', function () {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(submitForm, 350);
                });
            }

            selects.forEach(select => {
                select.addEventListener('change', submitForm);
            });
        });
    </script>
@endpush
@endsection