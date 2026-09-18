@extends('layouts.student')

@section('title', 'Dashboard')

@section('content')
    <main class="flex-1 p-8 grid grid-cols-12 gap-8 overflow-y-auto bg-gray-50/50 main-grid">

        @if(isset($violations) && count($violations) > 0)
            <!-- ** Left Column: Active Violation List ** -->
            <section class="col-span-7 space-y-4">
                @foreach($violations as $index => $violation)
                    <div class="grid grid-cols-5 items-center bg-white border-t-4 border-b-4 border-grc-red rounded-lg px-5 py-4 text-center font-bold shadow-md hover:shadow-lg transition-shadow">
                        <div class="col-span-3 text-left text-grc-red text-lg font-black pl-2 uppercase tracking-wide">
                            {{ $violation->violation_type }}
                        </div>
                        <div class="flex flex-col items-center justify-center gap-1">
                            <svg class="h-9 w-9 text-yellow-400 drop-shadow" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-grc-red text-xs tracking-tight">PENALTY {{ $index + 1 }}</span>
                        </div>
                        <div class="flex justify-center">
                            @if($violation->status === 'pending')
                                <button class="bg-red-600 text-white text-sm px-6 py-2 rounded-full font-bold shadow hover:bg-red-700 transition">PENDING</button>
                            @else
                                <button class="penalty-btn-gradient text-white text-sm px-6 py-2 rounded-full font-bold shadow hover:opacity-90 transition">PENALTY QUIZ</button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </section>

            <!-- ** Right Column: Violation Breakdown Cards ** -->
            <section class="col-span-5 space-y-6">
                <div class="flex items-center gap-6 bg-white p-6 rounded-2xl shadow-sm border border-gray-200/80">
                    <div class="w-16 h-16 flex items-center justify-center">
                        <svg class="w-14 h-14 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">TOTAL VIOLATIONS</h3>
                        <p class="text-grc-red text-5xl font-black tracking-tighter">{{ count($violations) }}</p>
                    </div>
                </div>

                <div class="flex gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-200/80">
                    <div class="w-12 h-12 flex-shrink-0 mt-1 flex items-center justify-center">
                        <svg class="w-10 h-10 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-grc-red text-xl font-extrabold tracking-tight mb-2">MINOR OFFENSE</h3>
                        <p class="text-gray-600 text-xs leading-relaxed font-medium">
                            Small-scale infractions that disrupt order but do not cause significant harm or safety risks. Often treated as correctable behaviors.
                        </p>
                    </div>
                </div>

                <div class="flex gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-200/80">
                    <div class="w-12 h-12 flex-shrink-0 mt-1 flex items-center justify-center">
                        <svg class="w-10 h-10 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-grc-red text-xl font-extrabold tracking-tight mb-2">MAJOR OFFENSE</h3>
                        <p class="text-gray-600 text-xs leading-relaxed font-medium">
                            Serious violations threatening the integrity, safety, or legal standing of the institution involving breaches of trust, ethics, or law.
                        </p>
                    </div>
                </div>
            </section>
        @else
            <!-- ** Left Column: Empty Clean Record State ** -->
            <section class="col-span-7 flex flex-col">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 p-8 flex-1 flex flex-col items-center justify-center text-center">
                    <div class="w-20 h-20 bg-green-50 text-green-500 rounded-full flex items-center justify-center text-4xl mb-4 shadow-inner">
                        🎉
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">No Violations Recorded</h3>
                    <p class="text-gray-500 text-sm max-w-md leading-relaxed">
                        Fantastic job! Your record is currently clean with zero reported violations. Keep up the great conduct and discipline inside the campus.
                    </p>
                </div>
            </section>

            <!-- ** Right Column: Clean Record Statistics & Reminder ** -->
            <section class="col-span-5 space-y-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200/80 flex items-center justify-between">
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">TOTAL VIOLATIONS</h3>
                        <p class="text-grc-red text-5xl font-black tracking-tighter">0</p>
                    </div>
                    <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center text-grc-red shadow-inner font-bold text-xl">
                        🛡️
                    </div>
                </div>

                <div class="bg-gradient-to-br from-red-50 to-white p-6 rounded-2xl shadow-sm border border-red-100">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="text-2xl">💡</span>
                        <h3 class="text-grc-red text-lg font-bold tracking-tight">Student Code Reminder</h3>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed font-medium">
                        Global Reciprocal Colleges values a safe, orderly, and respectful learning environment. Always wear your complete school uniform, maintain proper grooming, and respect fellow students and faculty members.
                    </p>
                </div>
            </section>
        @endif

    </main>
@endsection