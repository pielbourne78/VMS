@extends('layouts.admin')

@section('title', 'Violation Monitoring')

@section('content')
    <div class="mb-6 flex items-center justify-between gap-4">
        <div>
            <p class="text-xs font-black uppercase tracking-[0.28em] text-red-700">Operational Overview</p>
            <h2 class="mt-2 text-3xl font-black tracking-tight text-gray-900">Recent Violation</h2>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="rounded-full bg-red-700 px-4 py-2 text-sm font-black text-white shadow-sm transition hover:bg-red-800">
            Home
        </a>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-[0_16px_36px_rgba(15,23,42,0.08)]">
        <div class="max-h-[calc(100vh-240px)] overflow-auto admin-recent-scroll">
            <table class="min-w-[900px] w-full border-collapse text-left">
                <thead class="sticky top-0 z-10 bg-[#1d2d3d] text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-[0.2em]">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-[0.2em]">Violation type</th>
                        <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-[0.2em]">Violation code</th>
                        <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-[0.2em]">Sanction</th>
                        <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-[0.2em]">Date</th>
                        <th class="px-6 py-4 text-right text-xs font-black uppercase tracking-[0.2em]">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-gray-50">
                    @forelse ($violations ?? [] as $violation)
                        @php
                            $status = strtolower($violation->status ?? 'pending');
                            $statusLabel = $status === 'consequence_applied' ? 'Consequence Applied' : 'Pending';
                            $statusClass = $status === 'consequence_applied'
                                ? 'bg-blue-100 text-blue-800 ring-blue-200'
                                : 'bg-amber-100 text-amber-800 ring-amber-200';
                        @endphp
                        <tr class="text-lg text-gray-900 transition hover:bg-gray-100">
                            <td class="px-6 py-5 font-semibold">
                                {{ $violation->student?->full_name ?? $violation->student?->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-5 font-medium text-gray-700">{{ $violation->violation_type ?? 'N/A' }}</td>
                            <td class="px-6 py-5 font-black text-red-700">{{ $violation->violation_code ?? $violation->offense_code ?? 'N/A' }}</td>
                            <td class="px-6 py-5 text-gray-700">
                                {{ $violation->sanction_label ?? 'Not assigned yet' }}
                            </td>
                            <td class="px-6 py-5 text-sm leading-relaxed text-gray-700">
                                {{ $violation->occurred_at?->format('F d, Y') ?? 'N/A' }}<br>
                                {{ $violation->occurred_at?->format('h:i A') ?? '' }}
                            </td>
                            <td class="px-6 py-5 text-right">
                                <span class="inline-flex items-center rounded-full px-4 py-2 text-xs font-black uppercase tracking-wide ring-1 {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="bg-gray-50 px-8 py-8 text-center text-gray-500">
                                <div class="text-lg font-black text-gray-700">No recent violations recorded.</div>
                                <p class="mt-2 text-sm">New violation entries will appear here once they are logged.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <style>
        .admin-recent-scroll {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f3f4f6;
            scrollbar-gutter: stable;
        }

        .admin-recent-scroll::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        .admin-recent-scroll::-webkit-scrollbar-track {
            background: #f3f4f6;
        }

        .admin-recent-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
            border: 2px solid #f3f4f6;
        }

        .admin-recent-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
@endsection