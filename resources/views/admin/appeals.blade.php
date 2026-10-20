@extends('layouts.admin')

@section('title', 'Appeal History')

@section('content')
    @php
        $pendingCount = $attempts->where('admin_approved', null)->count();
        $approvedCount = $attempts->where('admin_approved', true)->count();
        $declinedCount = $attempts->where('admin_approved', false)->count();
    @endphp

    <div class="mx-auto w-full max-w-7xl space-y-6">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm font-black uppercase tracking-[0.2em] text-red-700">Appeal approval</p>
                <h2 class="mt-2 text-3xl font-black text-gray-900">Student reasoning review</h2>
                <p class="mt-1 text-sm text-gray-600">Approve resolved cases or decline pending cases after evaluation.</p>
            </div>
            <a href="{{ route('admin.appeals') }}"
                class="inline-flex items-center justify-center rounded-full border border-red-200 bg-red-50 px-4 py-2 text-sm font-bold text-red-700 transition hover:bg-red-100">
                Refresh queue
            </a>
        </div>

        @if(session('success'))
            <div class="rounded-2xl border border-green-300 bg-green-100 px-4 py-3 text-sm font-semibold text-green-700 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-4 md:grid-cols-4">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400">Total appeals</p>
                <p class="mt-2 text-3xl font-black text-gray-900">{{ $attempts->count() }}</p>
            </div>
            <div class="rounded-2xl border border-yellow-200 bg-yellow-50 p-5 shadow-sm">
                <p class="text-[10px] uppercase tracking-[0.2em] text-yellow-700">Pending</p>
                <p class="mt-2 text-3xl font-black text-yellow-600">{{ $pendingCount }}</p>
            </div>
            <div class="rounded-2xl border border-green-200 bg-green-50 p-5 shadow-sm">
                <p class="text-[10px] uppercase tracking-[0.2em] text-green-700">Approved</p>
                <p class="mt-2 text-3xl font-black text-green-600">{{ $approvedCount }}</p>
            </div>
            <div class="rounded-2xl border border-red-200 bg-red-50 p-5 shadow-sm">
                <p class="text-[10px] uppercase tracking-[0.2em] text-red-700">Disapproved</p>
                <p class="mt-2 text-3xl font-black text-red-600">{{ $declinedCount }}</p>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-red-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 bg-red-50 px-5 py-4">
                <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                    <h3 class="text-lg font-black text-gray-900">Submitted reasoning and violation appeals</h3>
                    <span class="text-xs font-bold uppercase tracking-[0.2em] text-red-700">Approved cases move to appeal reports</span>
                </div>
            </div>

            <div class="divide-y divide-gray-200">
                @forelse($attempts as $attempt)
                    @php
                        $violation = $attempt->quiz?->violation;
                        $answers = $attempt->answers ?? [];
                        $reasonText = collect($answers)->filter(fn ($answer) => is_string($answer) && trim($answer) !== '')
                            ->map(fn ($answer) => trim($answer))
                            ->implode("\n\n");
                    @endphp

                    <div class="p-5">
                        <div class="grid gap-5 lg:grid-cols-[1.2fr_1.5fr_1.2fr]">
                            <div class="space-y-3">
                                <div>
                                    <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400">Student</p>
                                    <p class="mt-1 text-lg font-black text-gray-900">{{ $attempt->user?->name ?? $attempt->user?->full_name ?? 'Student' }}</p>
                                    <p class="text-sm text-gray-500">{{ $attempt->user?->student_id ?? 'Student ID not available' }}</p>
                                </div>

                                <div>
                                    <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400">Violation</p>
                                    <p class="mt-1 font-bold text-gray-800">{{ $violation?->violation_type ?? 'Violation' }}</p>
                                    <p class="text-sm text-gray-500">{{ $violation?->violation_code ?? 'N/A' }} · {{ $violation?->offense_code ?? 'No offense code' }}</p>
                                </div>

                                <div>
                                    <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400">Case status</p>
                                    @if($attempt->admin_approved === null)
                                        <span class="mt-1 inline-flex rounded-full bg-yellow-100 px-2.5 py-1 text-[11px] font-black uppercase tracking-wide text-yellow-800">Pending review</span>
                                        <p class="mt-2 text-xs text-gray-500">Disapproved cases stay pending for further review.</p>
                                    @elseif($attempt->admin_approved)
                                        <span class="mt-1 inline-flex rounded-full bg-green-100 px-2.5 py-1 text-[11px] font-black uppercase tracking-wide text-green-800">Approved</span>
                                        <p class="mt-2 text-xs text-green-700">This case will be marked resolved and saved to the appeal history ledger.</p>
                                    @else
                                        <span class="mt-1 inline-flex rounded-full bg-red-100 px-2.5 py-1 text-[11px] font-black uppercase tracking-wide text-red-800">Disapproved</span>
                                        <p class="mt-2 text-xs text-red-700">This case remains pending and is kept open for follow-up action.</p>
                                    @endif
                                </div>
                            </div>

                            <div class="space-y-3">
                                <div>
                                    <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400">Student reasoning</p>
                                    <div class="mt-2 rounded-2xl border border-gray-200 bg-gray-50 p-3 text-sm text-gray-700 leading-relaxed">
                                        {{ $reasonText !== '' ? $reasonText : 'No reasoning submitted yet.' }}
                                    </div>
                                </div>

                                @if(!empty($attempt->admin_note))
                                    <div>
                                        <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400">Admin note</p>
                                        <div class="mt-2 rounded-2xl border border-red-100 bg-red-50 p-3 text-sm text-red-800">
                                            {{ $attempt->admin_note }}
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="space-y-4">
                                <div class="rounded-2xl border border-gray-200 bg-gray-50 p-3">
                                    <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400">Decision controls</p>
                                    <form method="POST" action="{{ route('admin.appeals.review', $attempt) }}" class="mt-3 space-y-3">
                                        @csrf
                                        @method('PATCH')

                                        <textarea name="admin_note" rows="3"
                                            class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-200"
                                            placeholder="Add decision note before approval or disapproval...">{{ old('admin_note', $attempt->admin_note ?? '') }}</textarea>

                                        <div class="flex flex-wrap gap-2">
                                            <button type="submit" formaction="{{ route('admin.appeals.approve', $attempt) }}" name="approved" value="1"
                                                class="rounded-full bg-green-600 px-4 py-2 text-[11px] font-black uppercase tracking-wide text-white transition hover:bg-green-700">
                                                Approve
                                            </button>
                                            <button type="submit" formaction="{{ route('admin.appeals.decline', $attempt) }}" name="approved" value="0"
                                                class="rounded-full bg-red-600 px-4 py-2 text-[11px] font-black uppercase tracking-wide text-white transition hover:bg-red-700">
                                                Decline
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                @if($violation)
                                    <a href="{{ route('admin.violations.history', $violation->user_id) }}"
                                        class="inline-flex items-center justify-center rounded-full border border-gray-300 bg-white px-4 py-2 text-xs font-bold uppercase tracking-wide text-gray-700 transition hover:bg-gray-100">
                                        View violation case
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-10 text-center text-gray-500">
                        <p class="text-lg font-bold">No student appeals are waiting for review.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection