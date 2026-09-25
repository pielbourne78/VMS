@extends('layouts.student')

@section('title', 'Penalty Quiz')

@section('content')
    <main class="flex-1 bg-[#efe8e8] p-6 md:p-10">
        <div class="mx-auto max-w-[1100px]">
            <div class="rounded-[28px] bg-[#d41e2b] p-6 text-white shadow-[0_20px_50px_rgba(166,22,34,0.25)] md:p-10">
                <div class="mb-8 flex items-center justify-between gap-6">
                    <div class="flex items-center gap-4">
                        <div
                            class="flex h-16 w-16 items-center justify-center rounded-full border-[3px] border-white/80 bg-white text-[20px] font-black text-[#b91c2f] shadow-lg">
                            GRC
                        </div>
                        <div class="leading-[1.05] text-[26px] font-black uppercase tracking-tight">
                            <div>Global</div>
                            <div>Reciprocal</div>
                            <div>Colleges</div>
                        </div>
                    </div>

                    <div class="hidden md:block text-right text-[24px] font-black uppercase leading-tight tracking-tight">
                        <div>Office of</div>
                        <div>Student</div>
                        <div>Affairs</div>
                    </div>
                </div>

                <form method="POST" action="{{ route('student.penalty.quiz.submit', $violation) }}" id="penaltyQuizForm">
                    @csrf

                    @if (session('error'))
                        <div
                            class="mb-5 rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-sm font-medium text-white">
                            {{ session('error') }}
                        </div>
                    @endif

                    @php
                        $question = $quiz->questions->first();
                    @endphp

                    @if($question)
                        <div class="mb-8 text-[38px] font-black leading-[1.2] tracking-tight text-white md:text-[48px]">
                            {{ $question->prompt }}
                        </div>

                        <div class="mb-8">
                            <label class="mb-3 block text-[22px] font-bold text-white">Reasoning:</label>
                            <textarea name="question_{{ $question->id }}" rows="4"
                                class="w-full rounded-[18px] border-0 border-b-2 border-white/80 bg-transparent px-0 py-2 text-[18px] text-white placeholder:text-white/80 focus:outline-none focus:ring-0"
                                placeholder="" required>{{ old('question_' . $question->id) }}</textarea>
                            <div class="mt-2 h-[2px] w-full bg-white/80"></div>
                        </div>
                    @endif

                    <div class="mt-8 flex items-center justify-between gap-4">
                        <a href="{{ route('dashboard') }}"
                            class="inline-flex min-w-[180px] items-center justify-center rounded-full bg-[#f2d9d9] px-8 py-4 text-[18px] font-black uppercase tracking-[0.08em] text-[#7a1d24] shadow-md hover:opacity-90">
                            Back
                        </a>
                        <button type="submit"
                            class="min-w-[180px] rounded-full bg-[#f2d9d9] px-8 py-4 text-[18px] font-black uppercase tracking-[0.08em] text-[#7a1d24] shadow-md hover:opacity-90">
                            Next
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection