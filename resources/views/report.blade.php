@extends('layouts.student')

@section('title', 'Report')

@section('content')
    <main class="flex-1 grc-red p-8 flex flex-col overflow-y-auto">
        <div class="flex items-center gap-3 mb-6">
            <h2 class="text-white font-extrabold text-lg tracking-wide underline underline-offset-4">
                Violation history:
            </h2>
        </div>

        <div class="bg-white rounded-2xl p-8 max-w-4xl w-full mx-auto shadow-2xl">
            @if ($violations->isEmpty())
                <div class="py-8 text-center text-gray-600">
                    <p class="text-lg font-bold text-grc-red uppercase tracking-wide">No violations recorded</p>
                    <p class="mt-2 text-sm">Your violation history will appear here once a case is logged.</p>
                </div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach ($violations as $violation)
                        @php
                            $status = strtolower($violation->status ?? 'pending');
                            $statusMeta = match ($status) {
                                'resolved' => ['label' => 'Resolved Case', 'icon' => '✓', 'dotClass' => 'bg-green-500 text-white', 'textClass' => 'text-green-600'],
                                'pending' => ['label' => 'Pending', 'icon' => '⚠️', 'dotClass' => 'bg-yellow-500 text-white', 'textClass' => 'text-yellow-600'],
                                'dismissed' => ['label' => 'Dismissed', 'icon' => '—', 'dotClass' => 'bg-gray-500 text-white', 'textClass' => 'text-gray-600'],
                                default => ['label' => 'Active Case', 'icon' => '⚠️', 'dotClass' => 'bg-red-500 text-white', 'textClass' => 'text-grc-red'],
                            };
                        @endphp

                        <div class="flex items-center justify-between py-4 gap-4">
                            <span class="text-grc-red font-bold text-sm uppercase tracking-wide">
                                {{ $violation->violation_type }}
                            </span>

                            <div class="flex items-center gap-2">
                                <span
                                    class="w-6 h-6 rounded-full {{ Str::startsWith($statusMeta['dotClass'], 'bg-') ? $statusMeta['dotClass'] : '' }} flex items-center justify-center text-xs font-bold">
                                    {{ $statusMeta['icon'] }}
                                </span>
                                <span class="font-bold text-sm uppercase tracking-wide {{ $statusMeta['textClass'] }}">
                                    {{ $statusMeta['label'] }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </main>
@endsection