@extends('layouts.admin')

@section('title', 'Apply Consequences')

@section('content')
    @php
        $selectedViolation = $violations->first();
    @endphp

    <div class="mx-auto w-full max-w-[1600px]">
        <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-[0.28em] text-red-700">Case Workflow</p>
                <h2 class="mt-2 text-3xl font-black tracking-tight text-gray-900">Consequence Review Queue</h2>
            </div>
            <div class="rounded-full bg-red-700 px-4 py-2 text-sm font-black text-white shadow-sm">
                {{ $violations->count() }} pending case{{ $violations->count() === 1 ? '' : 's' }}
            </div>
        </div>

    @if(session('success'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-bold text-green-800 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($selectedViolation)
        @php
            $selectedStatus = $selectedViolation->status ?? 'pending';
            $selectedStatusLabel = $selectedStatus === 'consequence_applied' ? 'Consequence Applied' : 'Pending';
            $selectedBadgeClass = $selectedStatus === 'consequence_applied'
                ? 'bg-blue-100 text-blue-800 ring-blue-200'
                : 'bg-amber-100 text-amber-800 ring-amber-200';
        @endphp

        <div class="mb-6 rounded-2xl border border-gray-200 bg-white p-5 shadow-[0_12px_28px_rgba(0,0,0,0.05)]">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <p class="text-[11px] font-black uppercase tracking-[0.25em] text-gray-500">Selected case</p>
                    <h3 class="mt-2 text-2xl font-black text-gray-900">
                        {{ $selectedViolation->student?->full_name ?? $selectedViolation->student?->name ?? 'Student' }}
                    </h3>
                </div>
                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-black uppercase tracking-wide ring-1 {{ $selectedBadgeClass }}">
                    {{ $selectedStatusLabel }}
                </span>
            </div>

            <div class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-xl bg-gray-50 p-3">
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-500">Violation code</p>
                    <p class="mt-2 text-base font-extrabold text-gray-900">{{ $selectedViolation->violation_code ?? $selectedViolation->offense_code ?? 'N/A' }}</p>
                </div>
                <div class="rounded-xl bg-gray-50 p-3">
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-500">Sanction</p>
                    <p class="mt-2 text-base font-extrabold text-gray-900">{{ $selectedViolation->sanction_label ?? 'Not assigned yet' }}</p>
                </div>
                <div class="rounded-xl bg-gray-50 p-3">
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-500">Violation</p>
                    <p class="mt-2 text-base font-extrabold text-gray-900">{{ $selectedViolation->violation_type ?? $selectedViolation->description ?? 'N/A' }}</p>
                </div>
                <div class="rounded-xl bg-gray-50 p-3">
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-500">Next step</p>
                    <p class="mt-2 text-base font-extrabold text-red-700">
                        {{ $selectedStatus === 'consequence_applied' ? 'Student must complete the penalty quiz.' : 'Apply consequence to unlock the student workflow.' }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-[0_18px_40px_rgba(15,23,42,0.08)]">
        <div class="max-h-[calc(100vh-260px)] overflow-auto admin-consequences-scroll">
            <table class="min-w-[1100px] w-full border-collapse text-left">
                <thead class="sticky top-0 z-10 bg-[#1d2d3d] text-white">
                    <tr>
                        <th class="px-5 py-4 text-xs font-black uppercase tracking-[0.2em]">Student</th>
                        <th class="px-5 py-4 text-xs font-black uppercase tracking-[0.2em]">Violation code</th>
                        <th class="px-5 py-4 text-xs font-black uppercase tracking-[0.2em]">Sanction</th>
                        <th class="px-5 py-4 text-xs font-black uppercase tracking-[0.2em]">Status</th>
                        <th class="px-5 py-4 text-xs font-black uppercase tracking-[0.2em] text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($violations as $violation)
                        @php
                            $status = $violation->status ?? 'pending';
                            $statusLabel = $status === 'consequence_applied' ? 'Consequence Applied' : 'Pending';
                            $statusClasses = $status === 'consequence_applied'
                                ? 'bg-blue-100 text-blue-800 ring-blue-200'
                                : 'bg-amber-100 text-amber-800 ring-amber-200';
                        @endphp
                        <tr class="align-top hover:bg-red-50/60">
                            <td class="px-5 py-4">
                                <div class="font-black text-gray-900">{{ $violation->student?->full_name ?? $violation->student?->name ?? 'Student' }}</div>
                                <div class="mt-1 text-xs text-gray-500">{{ $violation->student?->student_id ?? 'Student ID N/A' }}</div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-bold text-red-700">{{ $violation->violation_code ?? $violation->offense_code ?? 'N/A' }}</div>
                                <div class="mt-1 text-xs text-gray-600">{{ $violation->violation_type ?? $violation->description ?? 'No description' }}</div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-bold text-gray-900">{{ $violation->sanction_label ?? 'Not assigned yet' }}</div>
                                @if(!empty($violation->beyond_path))
                                    <div class="mt-1 text-[11px] font-bold uppercase tracking-wide text-red-700">Beyond sanctioned path</div>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-[11px] font-black uppercase tracking-wide ring-1 {{ $statusClasses }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <form action="{{ route('admin.violations.approve', $violation->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="rounded-full bg-red-700 px-5 py-2 text-xs font-black uppercase tracking-wide text-white shadow-sm transition hover:bg-red-800">
                                        Apply Consequence
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <div class="text-lg font-black text-gray-700">No pending violations found.</div>
                                <p class="mt-2 text-sm">All recorded cases are currently clear or already have consequences applied.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <style>
        .admin-consequences-scroll {
            max-height: calc(100vh - 260px);
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f8fafc;
            scrollbar-gutter: stable;
        }

        .admin-consequences-scroll::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        .admin-consequences-scroll::-webkit-scrollbar-track {
            background: #f8fafc;
        }

        .admin-consequences-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
            border: 2px solid #f8fafc;
        }

        .admin-consequences-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
@endsection