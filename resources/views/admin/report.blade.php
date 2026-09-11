@extends('layouts.admin')

@section('title', 'Report')

@section('content')
    <!-- Wide Rounded White Container Box -->
    <div class="bg-white rounded-3xl shadow-2xl flex-1 flex flex-col p-8 w-full overflow-hidden border border-gray-100">

        <!-- Table Headers -->
        <div class="grid grid-cols-3 font-black text-lg text-black px-8 mb-6 tracking-wide flex-shrink-0">
            <div>STUDENT NAME:</div>
            <div class="text-center">VIOLATION:</div>
            <div class="text-right pr-4">CASE STATUS:</div>
        </div>

        <!-- Scrollable Violation Rows Container -->
        <div class="space-y-4 overflow-y-auto pr-2 flex-1">
            @forelse($violations ?? [] as $violation)
                <div class="grid grid-cols-3 items-center bg-gray-200/70 rounded-full py-4 px-8 shadow-sm border border-gray-300/50">
                    <!-- Student Name -->
                    <div class="font-bold text-gray-800 text-base tracking-wide uppercase">
                        {{ $violation->user->name ?? 'N/A' }}
                    </div>

                    <!-- Violation Type -->
                    <div class="text-center font-black text-gray-900 text-base tracking-wider uppercase">
                        {{ $violation->violation_type ?? $violation->offense_name ?? $violation->title ?? 'N/A' }}
                    </div>

                    <!-- Case Status with Icon -->
                    <div class="flex items-center justify-end gap-3 font-extrabold text-sm tracking-wider">
                        @php $status = strtolower($violation->status ?? 'pending'); @endphp

                        @if($status === 'resolved')
                            <span class="text-gray-900">RESOLVED</span>
                            <span class="w-6 h-6 rounded-full bg-green-500 text-white flex items-center justify-center text-xs font-bold shadow">✓</span>
                        @else
                            <span class="text-gray-900 uppercase">PENDING</span>
                            <div class="w-6 h-6 rounded-full bg-red-600 text-white flex items-center justify-center text-[10px] font-black shadow">
                                <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <!-- Placeholder data rows -->
                <div class="grid grid-cols-3 items-center bg-gray-200/70 rounded-full py-4 px-8 shadow-sm border border-gray-300/50">
                    <div class="font-bold text-gray-800 text-base tracking-wide uppercase">Gab Baltazar</div>
                    <div class="text-center font-black text-gray-900 text-base tracking-wider uppercase">Nagma-madjong</div>
                    <div class="flex items-center justify-end gap-3 font-extrabold text-sm tracking-wider">
                        <span class="text-gray-900 uppercase">Pending</span>
                        <div class="w-6 h-6 rounded-full bg-red-600 text-white flex items-center justify-center text-[10px] font-black shadow">
                            <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center bg-gray-200/70 rounded-full py-4 px-8 shadow-sm border border-gray-300/50">
                    <div class="font-bold text-gray-800 text-base tracking-wide uppercase">Bob</div>
                    <div class="text-center font-black text-gray-900 text-base tracking-wider uppercase">Bullying</div>
                    <div class="flex items-center justify-end gap-3 font-extrabold text-sm tracking-wider">
                        <span class="text-gray-900">Resolved</span>
                        <span class="w-6 h-6 rounded-full bg-green-500 text-white flex items-center justify-center text-xs font-bold shadow">✓</span>
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center bg-gray-200/70 rounded-full py-4 px-8 shadow-sm border border-gray-300/50">
                    <div class="font-bold text-gray-800 text-base tracking-wide uppercase">Josh Agustin</div>
                    <div class="text-center font-black text-gray-900 text-base tracking-wider uppercase">Vandalism</div>
                    <div class="flex items-center justify-end gap-3 font-extrabold text-sm tracking-wider">
                        <span class="text-gray-900 uppercase">Pending</span>
                        <div class="w-6 h-6 rounded-full bg-red-600 text-white flex items-center justify-center text-[10px] font-black shadow">
                            <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

    </div>
@endsection