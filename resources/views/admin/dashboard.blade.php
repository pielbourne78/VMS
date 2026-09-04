@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <!-- Page Title -->
    <h2 class="text-2xl font-black text-gray-900 tracking-tight mb-8">ADMIN DASHBOARD</h2>

    <!-- Cards Stack -->
    <div class="space-y-6 max-w-5xl">

        <!-- Card 1: Total Violation -->
        <div class="card-red-gradient text-white rounded-xl p-5 shadow-lg flex items-center justify-between border border-red-600">
            <div class="flex items-center gap-6">
                <div class="bg-white/10 p-3 rounded-lg border border-white/20">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 class="text-2xl font-black tracking-wider">TOTAL VIOLATION</h3>
            </div>
            <a href="#" class="text-white text-sm font-bold tracking-tight hover:underline flex items-center gap-1">
                View details &gt;
            </a>
        </div>

        <!-- Card 2: Active Cases -->
        <div class="card-red-gradient text-white rounded-xl p-5 shadow-lg flex items-center justify-between border border-red-600">
            <div class="flex items-center gap-6">
                <div class="bg-white/10 p-3 rounded-lg border border-white/20">
                    <svg class="w-10 h-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.654 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-2xl font-black tracking-wider">ACTIVE CASES</h3>
            </div>
            <a href="#" class="text-white text-sm font-bold tracking-tight hover:underline flex items-center gap-1">
                View details &gt;
            </a>
        </div>

        <!-- Card 3: Pending Alert -->
        <div class="card-red-gradient text-white rounded-xl p-5 shadow-lg flex items-center justify-between border border-red-600">
            <div class="flex items-center gap-6">
                <div class="bg-white/10 p-3 rounded-lg border border-white/20">
                    <svg class="w-10 h-10 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-2xl font-black tracking-wider">PENDING ALERT</h3>
            </div>
            <a href="#" class="text-white text-sm font-bold tracking-tight hover:underline flex items-center gap-1">
                View details &gt;
            </a>
        </div>

        <!-- Card 4: Resolve Cases -->
        <div class="card-red-gradient text-white rounded-xl p-5 shadow-lg flex items-center justify-between border border-red-600">
            <div class="flex items-center gap-6">
                <div class="bg-white/10 p-3 rounded-lg border border-white/20">
                    <svg class="w-10 h-10 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-2xl font-black tracking-wider">RESOLVE CASES</h3>
            </div>
            <a href="#" class="text-white text-sm font-bold tracking-tight hover:underline flex items-center gap-1">
                View details &gt;
            </a>
        </div>

    </div>
@endsection