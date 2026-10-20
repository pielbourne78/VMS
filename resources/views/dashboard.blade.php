@extends('layouts.student')

@section('title', 'Dashboard')

@section('content')
    <main class="flex-1 p-6 lg:p-8 bg-[#f5f5f5]">
        <div class="max-w-7xl mx-auto space-y-6">
            @php
                $summary = $summary ?? ['total' => 0, 'active' => 0, 'pending' => 0, 'resolved' => 0, 'major' => 0, 'minor' => 0];
                $violations = $violations ?? collect();
                $totalViolations = is_countable($violations) ? count($violations) : 0;

                $sanctionNotifications = auth()->user()->notifications()->latest()->get()->filter(function ($notification) {
                    $data = $notification->data ?? [];
                    $type = $data['type'] ?? null;
                    $title = strtolower((string) ($data['title'] ?? ''));
                    $message = strtolower((string) ($data['message'] ?? ''));

                    return in_array($type, ['migration_warning', 'sanction_warning'], true)
                        || str_contains($title, 'sanction warning')
                        || str_contains($message, 'sanctions charged')
                        || str_contains($message, 'sanction warning');
                });
            @endphp

            @if($sanctionNotifications->isNotEmpty())
                @php
                    $latestSanction = $sanctionNotifications->first();
                    $sanctionData = $latestSanction->data ?? [];
                @endphp
                <div class="rounded-2xl border border-yellow-300 bg-yellow-50/80 p-5 shadow-sm">
                    <div class="flex items-start gap-4">
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-xl bg-yellow-100 text-yellow-600 text-2xl shadow-inner">
                            ⚠</div>
                        <div class="flex-1">
                            <h3 class="text-xl font-black uppercase tracking-wide text-red-700">Sanction Warning</h3>
                            <p class="mt-1 text-base font-semibold text-gray-700">
                                {{ $sanctionData['message'] ?? 'A sanction warning has been issued. Please review the details and complete the required action.' }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            @if($violations->isNotEmpty())
                <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
                    <section class="xl:col-span-7 space-y-4">
                        @foreach($violations as $violation)
                            @php
                                $status = strtolower((string) ($violation->status ?? 'pending'));
                                $category = strtolower((string) ($violation->offense_category ?? 'minor'));
                                $isMajor = $category === 'major';

                                $bar = $isMajor ? 'border-red-600' : match ($status) {
                                    'pending' => 'border-orange-500',
                                    'consequence_applied' => 'border-blue-500',
                                    default => 'border-green-500',
                                };

                                $sanctionText = $violation->sanction_label ?: ($violation->sanction ?? 'Sanction to be determined');
                                $offenseOrdinal = $violation->offense_ordinal ?? '1st';
                            @endphp

                            <div
                                class="grid grid-cols-1 sm:grid-cols-5 items-center gap-4 rounded-2xl border border-gray-200 bg-white border-l-8 {{ $bar }} px-5 py-4 shadow-sm">
                                <div class="sm:col-span-3 text-left">
                                    <p class="text-lg font-black uppercase tracking-wide text-red-700">
                                        {{ $violation->violation_type ?? 'Violation' }}
                                    </p>

                                    @if(!empty($violation->offense_code))
                                        <p class="mt-1 text-xs font-bold text-gray-600">
                                            {{ $violation->offense_code }} • {{ $isMajor ? 'Major' : 'Minor' }} • {{ $offenseOrdinal }}
                                            Offense
                                        </p>
                                        <p class="mt-1 text-xs text-gray-500">
                                            Sanction: {{ $sanctionText }}
                                            @if(!empty($violation->beyond_path))
                                                <span class="font-bold text-red-600">(beyond sanction path)</span>
                                            @endif
                                        </p>
                                    @endif
                                </div>

                                <div class="flex justify-center">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-full {{ $isMajor ? 'bg-red-100 text-red-600' : 'bg-yellow-100 text-yellow-500' }}">
                                        <svg class="h-7 w-7" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>

                                <div class="flex justify-center sm:justify-end">
                                    @if($status === 'pending')
                                        <button type="button"
                                            class="inline-flex items-center justify-center rounded-full bg-red-600 px-6 py-2 text-sm font-black uppercase tracking-wide text-white shadow hover:bg-red-700 transition cursor-default">
                                            Pending
                                        </button>
                                    @elseif($status === 'consequence_applied')
                                        <a href="{{ route('student.penalty.quiz', $violation) }}"
                                            class="inline-flex items-center justify-center rounded-full bg-red-600 px-6 py-2 text-sm font-black uppercase tracking-wide text-white shadow hover:bg-red-700 transition">
                                            Pending
                                        </a>
                                    @else
                                        <button type="button"
                                            class="inline-flex items-center justify-center rounded-full bg-green-600 px-6 py-2 text-sm font-black uppercase tracking-wide text-white shadow">
                                            Resolved
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </section>

                    <aside class="xl:col-span-5 space-y-6">
                        <div class="flex items-center gap-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-red-50 text-red-600">
                                <svg class="h-10 w-10" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="mb-1 text-xs font-black uppercase tracking-[0.2em] text-gray-500">Total Violations
                                </h3>
                                <p class="text-5xl font-black leading-none tracking-tighter text-red-600">
                                    {{ $summary['total'] ?? $totalViolations }}
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="rounded-2xl border border-gray-200 bg-white p-4 text-center shadow-sm">
                                <div class="text-xs font-black uppercase tracking-[0.2em] text-gray-500">Active</div>
                                <div class="mt-3 text-3xl font-black text-red-600">{{ $summary['active'] ?? 0 }}</div>
                            </div>
                            <div class="rounded-2xl border border-gray-200 bg-white p-4 text-center shadow-sm">
                                <div class="text-xs font-black uppercase tracking-[0.2em] text-gray-500">Pending</div>
                                <div class="mt-3 text-3xl font-black text-blue-600">{{ $summary['pending'] ?? 0 }}</div>
                            </div>
                            <div class="rounded-2xl border border-gray-200 bg-white p-4 text-center shadow-sm">
                                <div class="text-xs font-black uppercase tracking-[0.2em] text-gray-500">Resolved</div>
                                <div class="mt-3 text-3xl font-black text-green-600">{{ $summary['resolved'] ?? 0 }}</div>
                            </div>
                            <div class="rounded-2xl border border-gray-200 bg-white p-4 text-center shadow-sm">
                                <div class="text-xs font-black uppercase tracking-[0.2em] text-gray-500">Major</div>
                                <div class="mt-3 text-3xl font-black text-red-700">{{ $summary['major'] ?? 0 }}</div>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                            <div class="mb-2 flex items-center gap-4">
                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-full bg-orange-100 text-2xl text-orange-500">
                                    ⚠</div>
                                <div class="text-2xl font-black uppercase text-red-700">Minor Offense</div>
                            </div>
                            <p class="text-sm leading-relaxed text-gray-600">
                                Small-scale infractions that disrupt order but do not cause significant harm or safety risks.
                                Often treated as correctable behaviors.
                            </p>
                            <div class="mt-4 text-4xl font-black text-orange-500">{{ $summary['minor'] ?? 0 }}</div>
                        </div>

                        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                            <div class="mb-2 flex items-center gap-4">
                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100 text-2xl text-red-600">
                                    ⚠</div>
                                <div class="text-2xl font-black uppercase text-red-700">Major Offense</div>
                            </div>
                            <p class="text-sm leading-relaxed text-gray-600">
                                Serious violations involving significant risk, disruption, or misconduct that require immediate
                                attention and formal consequences.
                            </p>
                            <div class="mt-4 text-4xl font-black text-red-600">{{ $summary['major'] ?? 0 }}</div>
                        </div>
                    </aside>
                </div>
            @else
                <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
                    <section class="xl:col-span-7">
                        <div
                            class="flex min-h-[360px] flex-col items-center justify-center rounded-2xl border border-gray-200 bg-white p-8 text-center shadow-sm">
                            <div
                                class="mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-green-100 text-4xl text-green-600 shadow-inner">
                                🎉
                            </div>
                            <h3 class="mb-2 text-2xl font-black text-gray-800">No Violations Recorded</h3>
                            <p class="max-w-md text-sm leading-relaxed text-gray-500">
                                Fantastic job! Your record is currently clean with zero reported violations. Keep up the great
                                conduct and discipline inside the campus.
                            </p>
                        </div>
                    </section>

                    <aside class="xl:col-span-5 space-y-6">
                        <div
                            class="flex items-center justify-between rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                            <div>
                                <h3 class="mb-1 text-xs font-black uppercase tracking-[0.2em] text-gray-400">Total Violations
                                </h3>
                                <p class="text-5xl font-black tracking-tighter text-red-600">0</p>
                            </div>
                            <div
                                class="flex h-14 w-14 items-center justify-center rounded-2xl bg-red-50 text-2xl text-red-600 shadow-inner">
                                🛡️
                            </div>
                        </div>

                        <div class="rounded-2xl border border-red-100 bg-gradient-to-br from-red-50 to-white p-6 shadow-sm">
                            <div class="mb-3 flex items-center gap-3">
                                <span class="text-2xl">💡</span>
                                <h3 class="text-lg font-black uppercase tracking-tight text-red-700">Student Code Reminder</h3>
                            </div>
                            <p class="text-sm leading-relaxed text-gray-600">
                                Global Reciprocal Colleges values a safe, orderly, and respectful learning environment. Always
                                wear your complete school uniform, maintain proper grooming, and respect fellow students and
                                faculty members.
                            </p>
                        </div>
                    </aside>
                </div>
            @endif
        </div>
    </main>
@endsection