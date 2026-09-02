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
        <div class="bg-white rounded-2xl p-8 max-w-4xl w-full mx-auto shadow-2xl">

            <div class="divide-y divide-gray-100">

                <!-- Row: Resolved -->
                <div class="flex items-center justify-between py-4">
                    <span class="text-grc-red font-bold text-sm uppercase tracking-wide">Not Wearing Proper Uniform</span>
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center text-white text-xs">✓</span>
                        <span class="text-green-600 font-bold text-sm uppercase tracking-wide">Resolved Case</span>
                    </div>
                </div>

                <!-- Row: Active -->
                <div class="flex items-center justify-between py-4">
                    <span class="text-grc-red font-bold text-sm uppercase tracking-wide">Vandalism</span>
                    <div class="flex items-center gap-2">
                        <span class="text-grc-red text-lg">⚠️</span>
                        <span class="text-grc-red font-bold text-sm uppercase tracking-wide">Active Case</span>
                    </div>
                </div>

                <!-- Row: Active -->
                <div class="flex items-center justify-between py-4">
                    <span class="text-grc-red font-bold text-sm uppercase tracking-wide">Bullying</span>
                    <div class="flex items-center gap-2">
                        <span class="text-grc-red text-lg">⚠️</span>
                        <span class="text-grc-red font-bold text-sm uppercase tracking-wide">Active Case</span>
                    </div>
                </div>

                <!-- Row: Active (yellow variant) -->
                <div class="flex items-center justify-between py-4">
                    <span class="text-grc-red font-bold text-sm uppercase tracking-wide">Colored Hair</span>
                    <div class="flex items-center gap-2">
                        <span class="text-yellow-500 text-lg">⚠️</span>
                        <span class="text-grc-red font-bold text-sm uppercase tracking-wide">Active Case</span>
                    </div>
                </div>

                <!-- Row: Active -->
                <div class="flex items-center justify-between py-4">
                    <span class="text-grc-red font-bold text-sm uppercase tracking-wide">Thief</span>
                    <div class="flex items-center gap-2">
                        <span class="text-grc-red text-lg">⚠️</span>
                        <span class="text-grc-red font-bold text-sm uppercase tracking-wide">Active Case</span>
                    </div>
                </div>

                <!-- Row: Pending -->
                <div class="flex items-center justify-between py-4">
                    <span class="text-grc-red font-bold text-sm uppercase tracking-wide">Smoking</span>
                    <div class="flex items-center gap-2">
                        <span class="text-grc-red text-lg">⚠️</span>
                        <span class="text-grc-red font-bold text-sm uppercase tracking-wide">Pending</span>
                    </div>
                </div>

            </div>

        </div>

    </main>
@endsection