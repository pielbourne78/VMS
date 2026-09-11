@extends('layouts.student')

@section('title', 'Violation Monitoring')

@section('content')
    <main class="flex-1 grc-red p-8 flex flex-col overflow-y-auto">

        <!-- Tabs Navigation -->
        <div class="flex justify-center gap-12 text-white font-bold text-base mb-8">
            <div class="cursor-pointer pb-1 transition-all border-b-2 border-transparent" id="tab-0" onclick="switchTab(0)">ACTIVE CASE ({{ $activeCases->count() }})</div>
            <div class="cursor-pointer pb-1 transition-all border-b-2 border-transparent" id="tab-1" onclick="switchTab(1)">RESOLVED CASE ({{ $resolvedCases->count() }})</div>
            <div class="cursor-pointer pb-1 transition-all border-b-2 border-transparent" id="tab-2" onclick="switchTab(2)">TOTAL VIOLATION ({{ $totalViolations->count() }})</div>
            <div class="cursor-pointer pb-1 transition-all border-b-2 border-transparent" id="tab-3" onclick="switchTab(3)">PENDING ({{ $pendingCases->count() }})</div>
        </div>

        <!-- White Card Container -->
        <div class="bg-white rounded-3xl p-8 max-w-4xl w-full mx-auto shadow-2xl min-h-[350px]">

            <!-- TAB 0: ACTIVE CASE -->
            <div class="tab-content" id="content-0">
                <div class="grid grid-cols-3 font-bold text-grc-red text-base border-b-2 border-gray-200 pb-4 mb-6">
                    <div>CODE | VIOLATION:</div>
                    <div>DESCRIPTION</div>
                    <div>DUE DATE / OCCURRED:</div>
                </div>
                @forelse($activeCases as $violation)
                    <div class="grid grid-cols-3 py-3 border-b border-gray-100 text-sm font-medium">
                        <div class="font-bold text-red-700">{{ $violation->violation_code }} - {{ $violation->violation_type }}</div>
                        <div>{{ $violation->description ?? 'N/A' }}</div>
                        <div>{{ $violation->occurred_at->format('M d, Y') }}</div>
                    </div>
                @empty
                    <div class="text-center text-gray-400 italic py-16 text-sm">
                        No active case records found.
                    </div>
                @endforelse
            </div>

            <!-- TAB 1: RESOLVED CASE -->
            <div class="tab-content hidden" id="content-1">
                <div class="grid grid-cols-3 font-bold text-grc-red text-base border-b-2 border-gray-200 pb-4 mb-6">
                    <div>CODE | VIOLATION:</div>
                    <div>RESOLUTION NOTES:</div>
                    <div>DONE:</div>
                </div>
                @forelse($resolvedCases as $violation)
                    <div class="grid grid-cols-3 py-3 border-b border-gray-100 text-sm font-medium">
                        <div class="font-bold text-red-700">{{ $violation->violation_code }} - {{ $violation->violation_type }}</div>
                        <div>{{ $violation->resolution_notes ?? 'Resolved' }}</div>
                        <div>{{ $violation->resolved_at ? $violation->resolved_at->format('M d, Y') : 'N/A' }}</div>
                    </div>
                @empty
                    <div class="text-center text-gray-400 italic py-16 text-sm">
                        No resolved case records found.
                    </div>
                @endforelse
            </div>

            <!-- TAB 2: TOTAL VIOLATION -->
            <div class="tab-content hidden" id="content-2">
                <div class="grid grid-cols-3 font-bold text-grc-red text-base border-b-2 border-gray-200 pb-4 mb-6">
                    <div>CODE | VIOLATION:</div>
                    <div>STATUS</div>
                    <div>DATE</div>
                </div>
                @forelse($totalViolations as $violation)
                    <div class="grid grid-cols-3 py-3 border-b border-gray-100 text-sm font-medium">
                        <div class="font-bold text-red-700">{{ $violation->violation_code }} - {{ $violation->violation_type }}</div>
                        <div class="uppercase text-xs font-bold px-2 py-1 rounded w-fit bg-gray-100">{{ $violation->status }}</div>
                        <div>{{ $violation->occurred_at->format('M d, Y') }}</div>
                    </div>
                @empty
                    <div class="text-center text-gray-400 italic py-16 text-sm">
                        No total violation records found.
                    </div>
                @endforelse
            </div>

            <!-- TAB 3: PENDING -->
            <div class="tab-content hidden" id="content-3">
                <div class="grid grid-cols-3 font-bold text-grc-red text-base border-b-2 border-gray-200 pb-4 mb-6">
                    <div>CODE | VIOLATION:</div>
                    <div>DETAILS</div>
                    <div>STATUS</div>
                </div>
                @forelse($pendingCases as $violation)
                    <div class="grid grid-cols-3 py-3 border-b border-gray-100 text-sm font-medium">
                        <div class="font-bold text-red-700">{{ $violation->violation_code }} - {{ $violation->violation_type }}</div>
                        <div>{{ $violation->description ?? 'N/A' }}</div>
                        <div class="text-yellow-600 font-bold uppercase text-xs">{{ $violation->status }}</div>
                    </div>
                @empty
                    <div class="text-center text-gray-400 italic py-16 text-sm">
                        No pending records found.
                    </div>
                @endforelse
            </div>

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