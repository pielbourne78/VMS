@extends('layouts.admin')

@section('title', 'Violation Monitoring')

@section('content')
    <h2 class="bg-red-700 text-white text-3xl font-black tracking-tight px-8 py-8 shadow-sm -mx-10 -mt-8 mb-8">RECENT
        VIOLATION</h2>

    <div class="w-full rounded-2xl shadow-[0_12px_30px_rgba(0,0,0,0.08)] border border-gray-200 overflow-hidden bg-white">
        <div class="max-h-[460px] overflow-y-auto overflow-x-hidden admin-recent-scroll">
            <table class="w-full border-collapse min-w-[760px]">
                <thead class="sticky top-0 z-10">
                    <tr class="bg-[#1d2d3d] text-white text-sm font-black uppercase tracking-wider">
                        <th class="text-left px-8 py-4">Name</th>
                        <th class="text-left px-8 py-4">Violation Type</th>
                        <th class="text-center px-8 py-4">Date</th>
                        <th class="text-right px-8 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($violations ?? [] as $violation)
                        <tr class="bg-gray-100 text-lg text-gray-950 hover:bg-gray-200/70 transition">
                            <td class="px-8 py-6 font-semibold">
                                {{ $violation->student->full_name ?? $violation->student->name ?? 'N/A' }}
                            </td>
                            <td class="px-8 py-6">{{ $violation->violation_type }}</td>
                            <td class="text-center px-8 py-6 text-sm leading-tight">
                                {{ $violation->occurred_at?->format('F d, Y') ?? 'N/A' }}<br>
                                {{ $violation->occurred_at?->format('h:i A') ?? '' }}
                            </td>
                            <td class="text-right px-8 py-6">
                                @php $status = strtolower($violation->status ?? 'pending'); @endphp
                                <span
                                    class="inline-block rounded-full {{ $status === 'resolved' ? 'bg-green-600' : 'bg-red-600' }} px-5 py-2 text-white text-sm font-bold">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-8 text-center text-gray-500 bg-gray-100 font-bold">
                                No recent violations recorded.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <style>
        .admin-recent-scroll::-webkit-scrollbar {
            width: 10px;
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