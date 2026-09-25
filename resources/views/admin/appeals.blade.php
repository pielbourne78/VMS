@extends('layouts.admin')

@section('title', 'Review Appeals')

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-2xl font-black uppercase tracking-tight text-gray-800">Review Appeals</h2>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-lg border border-green-300 bg-green-100 px-4 py-3 text-sm font-semibold text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-2xl border border-red-200 bg-white shadow-sm">
            <table class="min-w-full text-left">
                <thead class="bg-red-50 text-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-sm font-black uppercase">Student</th>
                        <th class="px-4 py-3 text-sm font-black uppercase">Violation</th>
                        <th class="px-4 py-3 text-sm font-black uppercase">Reasoning</th>
                        <th class="px-4 py-3 text-sm font-black uppercase">Status</th>
                        <th class="px-4 py-3 text-sm font-black uppercase text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($attempts as $attempt)
                        <tr class="align-top">
                            <td class="px-4 py-4 text-sm font-bold text-gray-800">
                                {{ $attempt->user->name ?? 'Student' }}
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-700">
                                {{ $attempt->quiz->violation->violation_type ?? 'Violation' }}
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-700">
                                @php
                                    $answers = $attempt->answers ?? [];
                                    $firstAnswer = collect($answers)->first();
                                @endphp
                                {{ $firstAnswer ?: 'No reasoning submitted.' }}
                            </td>
                            <td class="px-4 py-4 text-sm font-bold">
                                @if($attempt->admin_approved === null)
                                    <span class="rounded-full bg-yellow-100 px-2 py-1 text-yellow-800">Pending</span>
                                @elseif($attempt->admin_approved)
                                    <span class="rounded-full bg-green-100 px-2 py-1 text-green-800">Approved</span>
                                @else
                                    <span class="rounded-full bg-red-100 px-2 py-1 text-red-800">Disapproved</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center">
                                <form method="POST" action="{{ route('admin.appeals.review', $attempt) }}" class="space-y-3">
                                    @csrf
                                    @method('PATCH')
                                    <textarea name="admin_note" rows="3"
                                        class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm"
                                        placeholder="Admin note..."></textarea>
                                    <div class="flex justify-center gap-3">
                                        <button type="submit" name="approved" value="1"
                                            class="rounded-full bg-green-600 px-4 py-2 text-xs font-black uppercase text-white hover:bg-green-700">
                                            Approve
                                        </button>
                                        <button type="submit" name="approved" value="0"
                                            class="rounded-full bg-red-600 px-4 py-2 text-xs font-black uppercase text-white hover:bg-red-700">
                                            Disagree
                                        </button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-sm font-semibold text-gray-500">
                                No student appeals found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection