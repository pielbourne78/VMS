@extends('layouts.student')

@section('title', 'Dashboard')

@section('content')
    <main class="flex-1 p-8 grid grid-cols-12 gap-8 overflow-y-auto bg-gray-50/50 main-grid">

        @if(isset($violations) && count($violations) > 0)
            <!-- ** Left Column: Active Violation List ** -->
            <section class="col-span-7 space-y-4">
                @foreach($violations as $index => $violation)
                    <div class="grid grid-cols-5 items-center bg-white border border-gray-200/80 rounded-xl p-3 text-center font-bold shadow-sm">
                        <div class="col-span-3 text-left text-grc-red text-base pl-2 uppercase">
                            {{ $violation->title ?? $violation['title'] }}
                        </div>
                        <div class="flex justify-center">
                            @if(($violation->severity ?? $violation['severity']) === 'major')
                                <img src="https://raw.githubusercontent.com/carlvilla/resources/main/alert-red.png" alt="Major" class="h-8 w-8">
                            @else
                                <img src="https://raw.githubusercontent.com/carlvilla/resources/main/alert-yellow.png" alt="Minor" class="h-8 w-8">
                            @endif
                        </div>
                        <div class="flex flex-col items-center gap-1">
                            <span class="text-grc-red text-xs tracking-tight">PENALTY {{ $index + 1 }}</span>
                            @if(($violation->status ?? $violation['status']) === 'pending')
                                <button class="bg-grc-red text-white text-xs px-4 py-1 rounded-full font-semibold shadow hover:bg-red-700">PENDING</button>
                            @else
                                <button class="penalty-btn-gradient text-white text-xs px-4 py-1 rounded-full font-semibold shadow">PENALTY QUIZ</button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </section>

            <!-- ** Right Column: Violation Breakdown Cards ** -->
            <section class="col-span-5 space-y-6">
                <div class="flex items-center gap-6 bg-white p-6 rounded-2xl shadow-sm border border-gray-200/80">
                    <img src="https://raw.githubusercontent.com/carlvilla/resources/main/alert-yellow.png" alt="Total" class="w-16 h-16">
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">TOTAL VIOLATIONS</h3>
                        <p class="text-grc-red text-5xl font-black tracking-tighter">{{ count($violations) }}</p>
                    </div>
                </div>

                <div class="flex gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-200/80">
                    <img src="https://raw.githubusercontent.com/carlvilla/resources/main/alert-yellow.png" alt="Minor" class="w-12 h-12 flex-shrink-0 mt-1">
                    <div>
                        <h3 class="text-grc-red text-xl font-extrabold tracking-tight mb-2">MINOR OFFENSE</h3>
                        <p class="text-gray-600 text-xs leading-relaxed font-medium">
                            Small-scale infractions that disrupt order but do not cause significant harm or safety risks. Often treated as correctable behaviors.
                        </p>
                    </div>
                </div>

                <div class="flex gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-200/80">
                    <img src="https://raw.githubusercontent.com/carlvilla/resources/main/alert-red.png" alt="Major" class="w-12 h-12 flex-shrink-0 mt-1">
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