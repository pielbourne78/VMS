@extends('layouts.student')

@section('title', 'Violation Monitoring')

@section('content')
    <main class="flex-1 grc-red p-8 flex flex-col overflow-y-auto">

        <!-- Tabs Navigation -->
        <div class="flex justify-center gap-12 text-white font-bold text-base mb-8">
            <div class="cursor-pointer pb-1 transition-all border-b-2 border-transparent" id="tab-0" onclick="switchTab(0)">ACTIVE CASE</div>
            <div class="cursor-pointer pb-1 transition-all border-b-2 border-transparent" id="tab-1" onclick="switchTab(1)">RESOLVED CASE</div>
            <div class="cursor-pointer pb-1 transition-all border-b-2 border-transparent" id="tab-2" onclick="switchTab(2)">TOTAL VIOLATION</div>
            <div class="cursor-pointer pb-1 transition-all border-b-2 border-transparent" id="tab-3" onclick="switchTab(3)">PENDING</div>
        </div>

        <!-- White Card Container -->
        <div class="bg-white rounded-3xl p-8 max-w-4xl w-full mx-auto shadow-2xl min-h-[350px]">

            <!-- TAB 0: ACTIVE CASE -->
            <div class="tab-content" id="content-0">
                <div class="grid grid-cols-3 font-bold text-grc-red text-base border-b-2 border-gray-200 pb-4 mb-6">
                    <div>CODE | VIOLATION:</div>
                    <div>SANCTIONS</div>
                    <div>DUE DATE:</div>
                </div>
                <div class="text-center text-gray-400 italic py-16 text-sm">
                    No active case records found. Data will appear here once added by the admin.
                </div>
            </div>

            <!-- TAB 1: RESOLVED CASE -->
            <div class="tab-content hidden" id="content-1">
                <div class="grid grid-cols-3 font-bold text-grc-red text-base border-b-2 border-gray-200 pb-4 mb-6">
                    <div>CODE | VIOLATION:</div>
                    <div>PENALTY LIST:</div>
                    <div>DONE:</div>
                </div>
                <div class="text-center text-gray-400 italic py-16 text-sm">
                    No resolved case records found. Data will appear here once updated by the admin.
                </div>
            </div>

            <!-- TAB 2: TOTAL VIOLATION -->
            <div class="tab-content hidden" id="content-2">
                <div class="grid grid-cols-3 font-bold text-grc-red text-base border-b-2 border-gray-200 pb-4 mb-6">
                    <div>CODE | VIOLATION:</div>
                    <div>STATUS</div>
                    <div>DATE</div>
                </div>
                <div class="text-center text-gray-400 italic py-16 text-sm">
                    No total violation records found.
                </div>
            </div>

            <!-- TAB 3: PENDING -->
            <div class="tab-content hidden" id="content-3">
                <div class="grid grid-cols-3 font-bold text-grc-red text-base border-b-2 border-gray-200 pb-4 mb-6">
                    <div>CODE | VIOLATION:</div>
                    <div>DETAILS</div>
                    <div>STATUS</div>
                </div>
                <div class="text-center text-gray-400 italic py-16 text-sm">
                    No pending records found.
                </div>
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