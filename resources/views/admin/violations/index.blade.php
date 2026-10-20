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
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-black text-gray-900">Track Violation History</h2>
        <a href="{{ route('admin.dashboard') }}"
            class="grc-red text-white rounded-full px-4 py-2 text-sm font-bold shadow">Home</a>
    </div>

    

    <form method="GET" action="{{ route('admin.violations.index') }}" id="violationFilterForm"
        class="mb-4 w-full max-w-[1500px] mx-auto">
        <div class="rounded-2xl border border-gray-200 bg-white p-2.5 shadow-sm">
            <div class="flex flex-col gap-3 md:flex-row md:items-center">
                <div class="relative flex-1 min-w-[220px]">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg">⌕</span>
                    <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                        placeholder="Search student name or ID..."
                        class="w-full border border-gray-300 rounded-full pl-11 pr-4 py-2.5 text-sm font-semibold focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-100">
                </div>

                <div class="flex items-center gap-2 md:ml-auto">
                    <button type="button" id="toggleFiltersBtn"
                        class="border border-gray-300 text-gray-700 rounded-full px-4 py-2.5 text-sm font-bold hover:bg-gray-50 min-w-[120px]">
                        More filters
                    </button>
                    <button type="submit"
                        class="grc-red text-white rounded-full px-5 py-2.5 text-sm font-bold shadow hover:opacity-90 min-w-[120px]">Filter</button>
                    <a href="{{ route('admin.violations.index') }}"
                        class="border border-gray-300 text-gray-700 rounded-full px-5 py-2.5 text-sm font-bold hover:bg-gray-50 min-w-[120px] text-center">Clear</a>
                </div>
            </div>
        </div>

        <div id="advancedFiltersOverlay" class="fixed inset-0 z-50 hidden bg-black/20 backdrop-blur-[1px]">
            <div class="absolute inset-x-0 top-20 mx-auto w-[min(1200px,92vw)] rounded-[28px] border border-gray-200 bg-white p-5 shadow-2xl">
                <div class="mb-4 flex items-center justify-between gap-3">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400">Search filters</p>
                        <h3 class="mt-1 text-xl font-black text-gray-900">Advanced case filters</h3>
                    </div>
                    <button type="button" id="closeFiltersBtn" class="rounded-full border border-gray-300 px-3 py-1.5 text-sm font-bold text-gray-700 hover:bg-gray-50">Close</button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-7 gap-3">
                    <div class="xl:col-span-7">
                        <div class="mb-3 flex flex-col gap-3 rounded-2xl border border-red-100 bg-red-50 p-3 md:flex-row md:items-center">
                            <label class="text-xs font-bold uppercase tracking-wide text-red-700">Bulk action</label>
                            <select name="bulk_action" form="violationFilterForm" class="border border-red-200 rounded-full bg-white px-3 py-2 text-sm font-semibold text-gray-700 focus:outline-none focus:border-red-500">
                                <option value="resolve">Resolve selected</option>
                                <option value="escalate">Escalate selected</option>
                                <option value="clear_escalation">Clear escalation</option>
                            </select>
                            <input type="text" name="bulk_reason" form="violationFilterForm" placeholder="Add a review note for escalation" class="w-full max-w-xs border border-red-200 rounded-full bg-white px-3 py-2 text-sm text-gray-700 focus:outline-none focus:border-red-500">
                        </div>
                    </div>

                    <label class="block">
                        <span class="block text-[11px] font-bold uppercase tracking-wide text-gray-500 mb-1">Course</span>
                        <select name="course"
                            class="w-full border border-gray-300 rounded-full px-3 py-2 text-sm font-semibold focus:outline-none focus:border-red-500">
                            <option value="">All Course</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course }}" {{ request('course') === $course ? 'selected' : '' }}>{{ $course }}
                                </option>
                            @endforeach
                        </select>
                    </label>

                    <label class="block">
                        <span class="block text-[11px] font-bold uppercase tracking-wide text-gray-500 mb-1">Section</span>
                        <select name="section"
                            class="w-full border border-gray-300 rounded-full px-3 py-2 text-sm font-semibold focus:outline-none focus:border-red-500">
                            <option value="">All Section</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section }}" {{ request('section') === $section ? 'selected' : '' }}>
                                    {{ $section }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="block">
                        <span class="block text-[11px] font-bold uppercase tracking-wide text-gray-500 mb-1">Year Level</span>
                        <select name="year_level"
                            class="w-full border border-gray-300 rounded-full px-3 py-2 text-sm font-semibold focus:outline-none focus:border-red-500">
                            <option value="">All Year Level</option>
                            @foreach ($yearLevels as $yearLevel)
                                <option value="{{ $yearLevel }}" {{ request('year_level') === $yearLevel ? 'selected' : '' }}>
                                    {{ $yearLevel }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="block">
                        <span class="block text-[11px] font-bold uppercase tracking-wide text-gray-500 mb-1">Status</span>
                        <select name="status"
                            class="w-full border border-gray-300 rounded-full px-3 py-2 text-sm font-semibold focus:outline-none focus:border-red-500">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                        </select>
                    </label>

                    <label class="block">
                        <span class="block text-[11px] font-bold uppercase tracking-wide text-gray-500 mb-1">Sort by</span>
                        <select name="sort_by"
                            class="w-full border border-gray-300 rounded-full px-3 py-2 text-sm font-semibold focus:outline-none focus:border-red-500">
                            <option value="occurred_at" {{ request('sort_by') === 'occurred_at' ? 'selected' : '' }}>Date</option>
                            <option value="violation_type" {{ request('sort_by') === 'violation_type' ? 'selected' : '' }}>Violation Type</option>
                            <option value="status" {{ request('sort_by') === 'status' ? 'selected' : '' }}>Status</option>
                            <option value="violation_code" {{ request('sort_by') === 'violation_code' ? 'selected' : '' }}>Violation Code</option>
                        </select>
                    </label>

                    <label class="block">
                        <span class="block text-[11px] font-bold uppercase tracking-wide text-gray-500 mb-1">Order</span>
                        <select name="sort_dir"
                            class="w-full border border-gray-300 rounded-full px-3 py-2 text-sm font-semibold focus:outline-none focus:border-red-500">
                            <option value="desc" {{ request('sort_dir', 'desc') === 'desc' ? 'selected' : '' }}>Descending</option>
                            <option value="asc" {{ request('sort_dir') === 'asc' ? 'selected' : '' }}>Ascending</option>
                        </select>
                    </label>

                    <div class="grid grid-cols-2 gap-2">
                        <label class="block">
                            <span class="block text-[11px] font-bold uppercase tracking-wide text-gray-500 mb-1">From</span>
                            <input type="date" name="date_from" value="{{ request('date_from') }}"
                                class="w-full border border-gray-300 rounded-full px-3 py-2 text-sm focus:outline-none focus:border-red-500">
                        </label>
                        <label class="block">
                            <span class="block text-[11px] font-bold uppercase tracking-wide text-gray-500 mb-1">To</span>
                            <input type="date" name="date_to" value="{{ request('date_to') }}"
                                class="w-full border border-gray-300 rounded-full px-3 py-2 text-sm focus:outline-none focus:border-red-500">
                        </label>
                    </div>
                </div>

                <div class="mt-5 flex items-center justify-end gap-2">
                    <button type="button" id="cancelFiltersBtn" class="border border-gray-300 rounded-full px-4 py-2 text-sm font-bold text-gray-700 hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="grc-red text-white rounded-full px-5 py-2.5 text-sm font-bold shadow hover:opacity-90">Apply filters</button>
                </div>
            </div>
        </div>
    </form>

    <div class="bg-white border border-gray-200 rounded-2xl shadow-[0_12px_30px_rgba(0,0,0,0.08)] overflow-hidden w-full max-w-[1500px] mx-auto">
        <div class="max-h-[calc(100vh-290px)] overflow-y-auto overflow-x-auto admin-scrollable-table">
            <table class="w-full text-left text-sm min-w-[980px]">
                <thead class="grc-red text-white uppercase text-xs font-bold sticky top-0 z-10">
                    <tr>
                        @if(($mode ?? 'violations') === 'students')
                            <th class="px-4 py-3">Student ID</th>
                            <th class="px-4 py-3">Student Name</th>
                            <th class="px-4 py-3">Course</th>
                            <th class="px-4 py-3">Total Violations</th>
                            <th class="px-4 py-3 text-right">Action</th>
                        @else
                            <th class="px-4 py-3"><input type="checkbox" id="select-all-violations" class="rounded border-gray-300"></th>
                            <th class="px-4 py-3">ID</th>
                            <th class="px-4 py-3">Student ID / Name</th>
                            <th class="px-4 py-3">Violation Type</th>
                            <th class="px-4 py-3">Date & Time</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Owner</th>
                            <th class="px-4 py-3 text-right">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @if(($mode ?? 'violations') === 'students')
                        @forelse ($students as $student)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-bold text-red-700">{{ $student->student_id ?? 'N/A' }}</td>
                                <td class="px-4 py-3 font-semibold">{{ $student->full_name ?? $student->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3">{{ $student->course ?? 'N/A' }}</td>
                                <td class="px-4 py-3 font-bold">{{ $student->violations_count }}</td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.violations.history', $student->id) }}" class="text-xs bg-red-600 px-3 py-1.5 rounded-full font-bold text-white hover:bg-red-700">View History</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-gray-400">No students with violations found.</td>
                            </tr>
                        @endforelse
                    @else
                        @forelse ($violations as $violation)
                            <tr class="hover:bg-gray-50 {{ $violation->isEscalated() ? 'bg-purple-50/40' : '' }}">
                                <td class="px-4 py-3">
                                    <input type="checkbox" name="violations[]" value="{{ $violation->id }}" class="violation-select rounded border-gray-300">
                                </td>
                                <td class="px-4 py-3 font-bold text-red-700">{{ $violation->violation_code }}</td>
                                <td class="px-4 py-3 font-semibold">
                                    <span class="text-red-700 font-bold">{{ $violation->student?->student_id ?? 'N/A' }}</span>
                                    <span class="text-gray-400"> · </span>
                                    {{ $violation->student?->full_name ?? $violation->student?->name ?? 'No Student' }}
                                </td>
                                <td class="px-4 py-3">{{ $violation->violation_type }}</td>
                                <td class="px-4 py-3 font-bold">{{ $violation->occurred_at?->format('M d, Y h:i A') ?? 'N/A' }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wide {{ $violation->status === 'resolved' ? 'bg-green-100 text-green-700' : ($violation->isEscalated() ? 'bg-purple-100 text-purple-700' : 'bg-yellow-100 text-yellow-700') }}">
                                        {{ $violation->isEscalated() ? 'Escalated' : ($violation->status === 'consequence_applied' ? 'Pending' : ($violation->status === 'pending' ? 'Active Case' : ucfirst($violation->status))) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-xs text-gray-600">
                                        <div class="font-bold text-gray-800">{{ $violation->caseOwner?->full_name ?? $violation->recordedBy?->full_name ?? 'Unassigned' }}</div>
                                        @if($violation->isEscalated())
                                            <div class="text-purple-700 font-semibold mt-1">Escalated</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    @if ($violation->student)
                                        <div class="flex items-center justify-end gap-2">
                                            <form method="POST" action="{{ route('admin.violations.escalate', $violation) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="reason" value="Assigned to review queue for operational follow-up.">
                                                <button type="submit" class="text-xs bg-purple-100 text-purple-700 px-3 py-1.5 rounded-full font-bold hover:bg-purple-200">Escalate</button>
                                            </form>
                                            <a href="{{ route('admin.violations.history', $violation->student->id) }}" class="text-xs bg-red-600 px-3 py-1.5 rounded-full font-bold text-white hover:bg-red-700">View History</a>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 font-bold">No Student</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-6 text-center text-gray-400">No violations recorded.</td>
                            </tr>
                        @endforelse
                    @endif
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-200 bg-gray-50 px-4 py-3">
            <div class="flex items-center justify-between gap-3">
                <div class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">
                    @if(($mode ?? '') === 'students')
                        {{ $students->firstItem() ?? 0 }}-{{ $students->lastItem() ?? 0 }} of {{ $students->total() }}
                    @else
                        {{ $violations->firstItem() ?? 0 }}-{{ $violations->lastItem() ?? 0 }} of {{ $violations->total() }}
                    @endif
                </div>
                <div class="flex items-center gap-2">
                    @if(($mode ?? '') === 'students')
                        {{ $students->onEachSide(1)->links('pagination::simple-tailwind') }}
                    @else
                        {{ $violations->onEachSide(1)->links('pagination::simple-tailwind') }}
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const masterCheckbox = document.getElementById('select-all-violations');
            if (!masterCheckbox) return;

            masterCheckbox.addEventListener('change', function () {
                document.querySelectorAll('.violation-select').forEach(function (checkbox) {
                    checkbox.checked = masterCheckbox.checked;
                });
            });
        });
    </script>

    <style>
        .admin-scrollable-table {
            max-height: calc(100vh - 290px);
            scrollbar-width: thin;
            scrollbar-color: #d1d5db #f3f4f6;
            scrollbar-gutter: stable;
        }

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

        .pagination a,
        .pagination span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 2.2rem;
            min-height: 2.2rem;
            padding: 0.45rem 0.75rem;
            border-radius: 9999px;
            border: 1px solid #e5e7eb;
            background: #fff;
            font-size: 0.75rem;
            font-weight: 700;
            color: #374151;
        }

        .pagination .disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .pagination .active span {
            background: #bf1e2e;
            border-color: #bf1e2e;
            color: #fff;
        }
    </style>
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('violationFilterForm');
            const searchInput = document.getElementById('searchInput');
            const overlay = document.getElementById('advancedFiltersOverlay');
            const toggleBtn = document.getElementById('toggleFiltersBtn');
            const closeBtn = document.getElementById('closeFiltersBtn');
            const cancelBtn = document.getElementById('cancelFiltersBtn');

            const openOverlay = () => {
                if (!overlay) return;
                overlay.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            };

            const closeOverlay = () => {
                if (!overlay) return;
                overlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            };

            if (searchInput) {
                searchInput.addEventListener('focus', openOverlay);
                searchInput.addEventListener('click', openOverlay);

                let debounceTimer;
                searchInput.addEventListener('input', function () {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        if (form) form.requestSubmit();
                    }, 350);
                });
            }

            if (toggleBtn) toggleBtn.addEventListener('click', openOverlay);
            if (closeBtn) closeBtn.addEventListener('click', closeOverlay);
            if (cancelBtn) cancelBtn.addEventListener('click', closeOverlay);

            if (overlay) {
                overlay.addEventListener('click', function (event) {
                    if (event.target === overlay) closeOverlay();
                });
            }

            if (form) {
                const selects = Array.from(form.querySelectorAll('select'));
                const submitForm = () => {
                    form.requestSubmit();
                };

                selects.forEach(select => {
                    select.addEventListener('change', function () {
                        closeOverlay();
                        submitForm();
                    });
                });
            }
        });
    </script>
@endpush
@endsection