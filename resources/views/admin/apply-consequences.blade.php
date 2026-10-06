@extends('layouts.admin')

@section('title', 'Apply Consequences')

@section('content')
    <h2 class="text-2xl font-black text-gray-800 tracking-tight mb-6 uppercase">CONSEQUENCES</h2>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg font-semibold text-sm max-w-4xl">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-blue-50/60 border-4 border-red-700 rounded-xl shadow-[0_12px_28px_rgba(191,30,46,0.1)] max-w-5xl overflow-hidden">
        <div class="max-h-[500px] overflow-y-auto admin-consequences-scroll">
            <table class="w-full text-left border-collapse min-w-[760px]">
                <thead class="sticky top-0 z-10">
                    <tr class="border-b-2 border-red-700 text-gray-800 bg-blue-100/80">
                        <th class="py-3 px-6 text-base font-extrabold">Student name</th>
                        <th class="py-3 px-6 text-base font-extrabold">Violation type</th>
                        <th class="py-3 px-6 text-base font-extrabold">Status</th>
                        <th class="py-3 px-6 text-base font-extrabold text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-blue-200">
                @forelse($violations as $violation)
                    <tr class="hover:bg-blue-100/40">
                        <td class="py-3 px-6 font-bold text-gray-800 text-sm">
                            {{ $violation->student->full_name ?? $violation->student->name ?? 'Student Name' }}
                        </td>
                        <td class="py-3 px-6 font-bold text-gray-700 uppercase text-sm">
                            {{ $violation->violation_type ?? $violation->description ?? 'N/A' }}
                        </td>
                        <td class="py-3 px-6 font-bold text-gray-700 uppercase text-sm">
                            {{ $violation->status }}
                        </td>
                        <td class="py-3 px-6 text-center">
                            <form action="{{ route('admin.violations.approve', $violation->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="bg-lime-500 hover:bg-lime-600 text-black font-extrabold px-5 py-1 rounded-full text-xs shadow transition">
                                    Approve
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-gray-500 font-bold">
                            No pending violations found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            </table>
        </div>
    </div>

    <style>
        .admin-consequences-scroll::-webkit-scrollbar {
            width: 10px;
        }

        .admin-consequences-scroll::-webkit-scrollbar-track {
            background: #eef2ff;
        }

        .admin-consequences-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
            border: 2px solid #eef2ff;
        }

        .admin-consequences-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
@endsection