@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    @php
        $stats = $stats ?? [
            'total' => 0,
            'active' => 0,
            'pending' => 0,
            'resolved' => 0,
            'major' => 0,
            'minor' => 0,
        ];
    @endphp

    <h2 class="text-2xl font-black text-gray-900 tracking-tight mb-8">ADMIN DASHBOARD</h2>

    <div class="grid gap-6 max-w-6xl lg:grid-cols-2">
        <div class="card-red-gradient text-white rounded-xl p-5 shadow-lg flex items-center justify-between border border-red-600">
            <div class="flex items-center gap-6">
                <div class="bg-white/10 p-3 rounded-lg border border-white/20">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-red-100">Total cases</p>
                    <h3 class="text-3xl font-black tracking-wider">{{ $stats['total'] }}</h3>
                </div>
            </div>
            <a href="{{ route('admin.violations.index') }}" class="text-white text-sm font-bold tracking-tight hover:underline flex items-center gap-1">View details &gt;</a>
        </div>

        <div class="card-red-gradient text-white rounded-xl p-5 shadow-lg flex items-center justify-between border border-red-600">
            <div class="flex items-center gap-6">
                <div class="bg-white/10 p-3 rounded-lg border border-white/20">
                    <svg class="w-10 h-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.654 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-red-100">Active cases</p>
                    <h3 class="text-3xl font-black tracking-wider">{{ $stats['active'] }}</h3>
                </div>
            </div>
            <a href="{{ route('admin.violations.index', ['status' => 'pending']) }}" class="text-white text-sm font-bold tracking-tight hover:underline flex items-center gap-1">View details &gt;</a>
        </div>

        <div class="card-red-gradient text-white rounded-xl p-5 shadow-lg flex items-center justify-between border border-red-600">
            <div class="flex items-center gap-6">
                <div class="bg-white/10 p-3 rounded-lg border border-white/20">
                    <svg class="w-10 h-10 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-red-100">Pending action</p>
                    <h3 class="text-3xl font-black tracking-wider">{{ $stats['pending'] }}</h3>
                </div>
            </div>
            <a href="{{ route('admin.violations.index', ['status' => 'consequence_applied']) }}" class="text-white text-sm font-bold tracking-tight hover:underline flex items-center gap-1">View details &gt;</a>
        </div>

        <div class="card-red-gradient text-white rounded-xl p-5 shadow-lg flex items-center justify-between border border-red-600">
            <div class="flex items-center gap-6">
                <div class="bg-white/10 p-3 rounded-lg border border-white/20">
                    <svg class="w-10 h-10 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-red-100">Resolved</p>
                    <h3 class="text-3xl font-black tracking-wider">{{ $stats['resolved'] }}</h3>
                </div>
            </div>
            <a href="{{ route('admin.violations.index', ['status' => 'resolved']) }}" class="text-white text-sm font-bold tracking-tight hover:underline flex items-center gap-1">View details &gt;</a>
        </div>
    </div>

    <div class="mt-8 max-w-6xl grid gap-6 md:grid-cols-3">
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <p class="text-xs uppercase tracking-[0.2em] text-gray-400">Major offenses</p>
            <p class="mt-2 text-3xl font-black text-red-600">{{ $stats['major'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <p class="text-xs uppercase tracking-[0.2em] text-gray-400">Minor offenses</p>
            <p class="mt-2 text-3xl font-black text-orange-500">{{ $stats['minor'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <p class="text-xs uppercase tracking-[0.2em] text-gray-400">Dismissed</p>
            <p class="mt-2 text-3xl font-black text-gray-600">{{ $stats['dismissed'] ?? 0 }}</p>
        </div>
    </div>

    @if(!empty($recent))
        <div class="mt-10 max-w-6xl bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="border-b border-gray-200 px-5 py-4">
                <h3 class="text-lg font-black text-gray-900">Recent case activity</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @foreach($recent as $item)
                    <div class="flex items-center justify-between gap-4 px-5 py-4">
                        <div>
                            <p class="font-bold text-gray-800">{{ $item->student?->full_name ?? 'Unknown student' }}</p>
                            <p class="text-sm text-gray-500">{{ $item->violation_type }} · {{ $item->offense_code ?? 'No code' }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-1 text-xs font-bold uppercase text-red-700">
                                {{ $item->status }}
                            </span>
                            @if($item->student)
                                <a href="{{ route('admin.violations.history', $item->student) }}" class="rounded-full bg-gray-900 px-3 py-1.5 text-xs font-bold text-white hover:bg-gray-700">Review</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endsection