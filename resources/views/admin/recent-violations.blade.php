@extends('layouts.admin')

@section('title', 'Violation Monitoring')

@section('content')
    <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="text-xs font-black uppercase tracking-[0.28em] text-red-700">Operational Overview</p>
            <h2 class="mt-2 text-3xl font-black tracking-tight text-gray-900">Recent Violation</h2>
        </div>

        <div class="flex w-full max-w-xl items-center gap-3 lg:justify-end">
            <form method="GET" action="{{ route('admin.violations.recent') }}" id="recent-violations-search-form"
                class="w-full max-w-md">
                <label for="recent-violation-search" class="relative block">
                    <span class="sr-only">Search recent violations</span>
                    <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M9 3.5a5.5 5.5 0 1 0 3.452 9.392l2.75 2.75a1 1 0 1 0 1.414-1.414l-2.75-2.75A5.5 5.5 0 0 0 9 3.5ZM5.5 9a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0Z"
                            clip-rule="evenodd" />
                    </svg>
                    <input id="recent-violation-search" name="search" type="search" value="{{ $search ?? '' }}"
                        placeholder="Search by name or violation type..."
                        class="w-full rounded-full border border-gray-200 bg-white py-2.5 pl-11 pr-12 text-sm font-medium text-gray-700 shadow-sm transition placeholder:text-gray-400 focus:border-red-300 focus:outline-none focus:ring-4 focus:ring-red-100">
                </label>
            </form>

            <a href="{{ route('admin.dashboard') }}"
                class="inline-flex items-center justify-center rounded-full bg-red-700 px-4 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-red-800">
                Home
            </a>
        </div>
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
                    @forelse ($violations as $violation)
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
                            <td class="px-6 py-5 font-black text-red-700">
                                {{ $violation->violation_code ?? $violation->offense_code ?? 'N/A' }}</td>
                            <td class="px-6 py-5 text-gray-700">
                                {{ $violation->sanction_label ?? 'Not assigned yet' }}
                            </td>
                            <td class="px-6 py-5 text-sm leading-relaxed text-gray-700">
                                {{ $violation->occurred_at?->format('F d, Y') ?? 'N/A' }}<br>
                                {{ $violation->occurred_at?->format('h:i A') ?? '' }}
                            </td>
                            <td class="px-6 py-5 text-right">
                                <span
                                    class="inline-flex items-center rounded-full px-4 py-2 text-xs font-black uppercase tracking-wide ring-1 {{ $statusClass }}">
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('recent-violations-search-form');
            const input = document.getElementById('recent-violation-search');

            if (!form || !input) {
                return;
            }

            let searchTimer = null;

            input.addEventListener('input', function () {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function () {
                    form.requestSubmit();
                }, 250);
            });
        });
    </script>

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