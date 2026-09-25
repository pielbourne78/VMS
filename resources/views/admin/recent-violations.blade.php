@extends('layouts.admin')

@section('title', 'Violation Monitoring')

@section('content')
    <h2 class="bg-red-700 text-white text-3xl font-black tracking-tight px-8 py-8 shadow-sm -mx-10 -mt-8 mb-8">RECENT VIOLATION</h2>

    <div class="w-full rounded-2xl overflow-hidden shadow-lg border border-gray-200">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-800 text-white text-sm font-black uppercase tracking-wider">
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
                            <span class="inline-block rounded-full {{ $status === 'resolved' ? 'bg-green-600' : 'bg-red-600' }} px-5 py-2 text-white text-sm font-bold">
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
@endsection