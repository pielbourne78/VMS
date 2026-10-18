@extends('layouts.student')

@section('title', 'Violation Monitoring')

@section('content')
    <main class="flex-1 grc-red p-8 flex flex-col overflow-y-auto">

        <div class="flex justify-center gap-12 text-white font-bold text-base mb-8">
            <div class="cursor-pointer pb-1 transition-all border-b-2 border-transparent" id="tab-0" onclick="switchTab(0)">ACTIVE CASE ({{ $activeCases->count() }})</div>
            <div class="cursor-pointer pb-1 transition-all border-b-2 border-transparent" id="tab-1" onclick="switchTab(1)">RESOLVED CASE ({{ $resolvedCases->count() }})</div>
            <div class="cursor-pointer pb-1 transition-all border-b-2 border-transparent" id="tab-2" onclick="switchTab(2)">TOTAL VIOLATION ({{ $totalViolations->count() }})</div>
            <div class="cursor-pointer pb-1 transition-all border-b-2 border-transparent" id="tab-3" onclick="switchTab(3)">PENDING ({{ $pendingCases->count() }})</div>
        </div>

        <div class="bg-white rounded-3xl p-8 max-w-5xl w-full mx-auto shadow-2xl min-h-[350px]">

            @php
                $tabs = [
                    0 => ['list' => $activeCases,   'empty' => 'No active case records found.'],
                    1 => ['list' => $resolvedCases, 'empty' => 'No resolved case records found.'],
                    2 => ['list' => $totalViolations, 'empty' => 'No total violation records found.'],
                    3 => ['list' => $pendingCases,  'empty' => 'No pending records found.'],
                ];
            @endphp

            @foreach($tabs as $i => $tab)
                <div class="tab-content {{ $i === 0 ? '' : 'hidden' }}" id="content-{{ $i }}">
                    <div class="grid grid-cols-12 gap-4 font-bold text-grc-red text-sm border-b-2 border-gray-200 pb-4 mb-4">
                        <div class="col-span-4">CODE | VIOLATION</div>
                        <div class="col-span-3">DETAILS</div>
                        <div class="col-span-3">SANCTION</div>
                        <div class="col-span-2 text-right">STATUS</div>
                    </div>

                    @forelse($tab['list'] as $violation)
                        <div class="grid grid-cols-12 gap-4 py-3 border-b border-gray-100 text-sm items-start">
                            <div class="col-span-4">@include('violations.partials.code-cell', ['violation' => $violation])</div>
                            <div class="col-span-3 text-gray-700">
                                {{ $violation->description ?? 'N/A' }}
                                <p class="text-[11px] text-gray-400 mt-0.5">{{ $violation->occurred_at->format('M d, Y') }}</p>
                            </div>
                            <div class="col-span-3 text-gray-700">
                                @if($violation->sanction_label)
                                    <span class="font-semibold">{{ $violation->sanction_label }}</span>
                                    @if($violation->beyond_path)
                                        <p class="text-[11px] text-red-600 font-bold">Beyond sanction path</p>
                                    @endif
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </div>
                            <div class="col-span-2 text-right">
                                <span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-bold uppercase {{ $violation->display_status_class }}">
                                    {{ $violation->display_status }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-400 italic py-16 text-sm">{{ $tab['empty'] }}</div>
                    @endforelse
                </div>
            @endforeach

        </div>
    </main>
@endsection

@push('scripts')
<script>
    function switchTab(index) {
        for (let i = 0; i < 4; i++) {
            const tabEl = document.getElementById('tab-' + i);
            const contentEl = document.getElementById('content-' + i);

            if (i === index) {
                tabEl.classList.add('border-white');
                tabEl.classList.remove('border-transparent', 'opacity-75');
                contentEl.classList.remove('hidden');
            } else {
                tabEl.classList.remove('border-white');
                tabEl.classList.add('border-transparent', 'opacity-75');
                contentEl.classList.add('hidden');
            }
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById('tab-0').classList.add('border-white');
    });
</script>
@endpush