@extends('layouts.student')

@section('title', 'Report')

@section('content')
    <main class="flex-1 grc-red p-8 flex flex-col overflow-y-auto">

        <!-- Section Title -->
        <div class="flex items-center gap-3 mb-6">
            <h2 class="text-white font-extrabold text-lg tracking-wide underline underline-offset-4">
                Violation history:
            </h2>
        </div>	

        <!-- White Card Container -->
        <div class="bg-white rounded-3xl p-8 max-w-4xl w-full mx-auto shadow-2xl min-h-[350px]">

            <div class="divide-y divide-gray-100">
                @forelse($violations ?? [] as $violation)
                    @php 
                        $status = strtolower($violation->status ?? 'pending');
                        $violationName = $violation->violation_type ?? $violation->offense_name ?? $violation->title ?? 'Violation';
                    @endphp

                    <div class="flex items-center justify-between py-4">
                        <!-- Violation Name -->
                        <span class="text-grc-red font-bold text-sm uppercase tracking-wide">{{ $violationName }}</span>
                        
                        <!-- Status Display -->
                        <div class="flex items-center gap-2">
                            @if($status === 'resolved' || $status === 'resolved case')
                                <span class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center text-white text-xs">✓</span>
                                <span class="text-green-600 font-bold text-sm uppercase tracking-wide">Resolved Case</span>
                            @elseif($status === 'active' || $status === 'active case')
                                <span class="text-grc-red text-lg">⚠️</span>
                                <span class="text-grc-red font-bold text-sm uppercase tracking-wide">Active Case</span>
                            @else
                                <span class="text-grc-red text-lg">⚠️</span>
                                <span class="text-grc-red font-bold text-sm uppercase tracking-wide">Pending</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-400 italic py-16 text-sm">
                    No violaton record found.
                    </div>
                @endforelse
            </div>

        </div>

    </main>
@endsection