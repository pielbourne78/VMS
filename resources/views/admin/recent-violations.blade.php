@extends('layouts.admin')

@section('title', 'Violation Monitoring')

@section('content')
    <h2 class="bg-red-600 text-white text-3xl font-black tracking-tight px-8 py-8 shadow-sm -mx-10 -mt-8 mb-8">RECENT VIOLATION</h2>

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
                @foreach ([
                    ['Juan Dela Cruz', 'Cheating'],
                    ['Maria Clara', 'Uniform'],
                    ['Pedro Reyes', 'Bullying'],
                    ['Lagman Cruz', 'Lost ID'],
                    ['Izzy Gianan', 'Smoking/Vaping'],
                ] as [$student, $violation])
                <tr class="bg-gray-100 text-lg text-gray-950 hover:bg-gray-200/70 transition">
                    <td class="px-8 py-6 font-semibold">{{ $student }}</td>
                    <td class="px-8 py-6">{{ $violation }}</td>
                    <td class="text-center px-8 py-6 text-sm leading-tight">April 1, 2026<br>7:30 AM</td>
                    <td class="text-right px-8 py-6">
                        <span class="inline-block rounded-full bg-red-600 px-5 py-2 text-white text-sm font-bold">Pending</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection