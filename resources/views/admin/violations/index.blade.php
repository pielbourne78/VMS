<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Violation Monitoring</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800;900&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }

        .grc-red {
            background-color: #BF1E2E;
        }

        .grc-red-border {
            border-color: #BF1E2E;
        }
    </style>
</head>

<body class="bg-gray-100 antialiased">
    <div class="max-w-7xl mx-auto px-6 py-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-black text-gray-900">Admin Violation Monitoring & Tracking</h2>
            <a href="{{ route('admin.dashboard') }}"
                class="grc-red text-white rounded-full px-4 py-2 text-sm font-bold shadow">Home</a>
        </div>

        <form method="GET" action="{{ route('admin.violations.index') }}"
            class="mb-6 bg-white border border-gray-200 rounded-2xl p-4 shadow-sm">
            <div class="flex flex-col lg:flex-row lg:items-center gap-3">
                <div class="relative flex-1 min-w-[220px]">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg">⌕</span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search student name or ID..."
                        class="w-full border border-gray-300 rounded-full pl-11 pr-4 py-2.5 text-sm font-semibold focus:outline-none focus:border-red-500">
                </div>

                <button type="submit"
                    class="grc-red text-white rounded-full px-5 py-2.5 text-sm font-bold shadow hover:opacity-90">Filter</button>
                <a href="{{ route('admin.violations.index') }}"
                    class="border border-gray-300 text-gray-700 rounded-full px-5 py-2.5 text-sm font-bold hover:bg-gray-50">Clear</a>
            </div>

            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-6 gap-3">
                <label class="block">
                    <span class="block text-[11px] font-bold uppercase tracking-wide text-gray-500 mb-1">Course</span>
                    <select name="course"
                        class="w-full border border-gray-300 rounded-full px-3 py-2 text-sm font-semibold focus:outline-none focus:border-red-500">
                        <option value="">All Course</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course }}" {{ request('course') === $course ? 'selected' : '' }}>{{ $course }}
                            </option>
                        @endforeach
                    </select>
                </label>

                <label class="block">
                    <span class="block text-[11px] font-bold uppercase tracking-wide text-gray-500 mb-1">Section</span>
                    <select name="section"
                        class="w-full border border-gray-300 rounded-full px-3 py-2 text-sm font-semibold focus:outline-none focus:border-red-500">
                        <option value="">All Section</option>
                        @foreach ($sections as $section)
                            <option value="{{ $section }}" {{ request('section') === $section ? 'selected' : '' }}>
                                {{ $section }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="block">
                    <span class="block text-[11px] font-bold uppercase tracking-wide text-gray-500 mb-1">Status</span>
                    <select name="status"
                        class="w-full border border-gray-300 rounded-full px-3 py-2 text-sm font-semibold focus:outline-none focus:border-red-500">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved
                        </option>
                    </select>
                </label>

                <label class="block">
                    <span class="block text-[11px] font-bold uppercase tracking-wide text-gray-500 mb-1">Sort by</span>
                    <select name="sort_by"
                        class="w-full border border-gray-300 rounded-full px-3 py-2 text-sm font-semibold focus:outline-none focus:border-red-500">
                        <option value="occurred_at" {{ request('sort_by') === 'occurred_at' ? 'selected' : '' }}>Date
                        </option>
                        <option value="violation_type" {{ request('sort_by') === 'violation_type' ? 'selected' : '' }}>
                            Violation Type</option>
                        <option value="status" {{ request('sort_by') === 'status' ? 'selected' : '' }}>Status</option>
                        <option value="violation_code" {{ request('sort_by') === 'violation_code' ? 'selected' : '' }}>
                            Violation Code</option>
                    </select>
                </label>

                <label class="block">
                    <span class="block text-[11px] font-bold uppercase tracking-wide text-gray-500 mb-1">Order</span>
                    <select name="sort_dir"
                        class="w-full border border-gray-300 rounded-full px-3 py-2 text-sm font-semibold focus:outline-none focus:border-red-500">
                        <option value="desc" {{ request('sort_dir', 'desc') === 'desc' ? 'selected' : '' }}>Descending
                        </option>
                        <option value="asc" {{ request('sort_dir') === 'asc' ? 'selected' : '' }}>Ascending</option>
                    </select>
                </label>

                <div class="grid grid-cols-2 gap-2">
                    <label class="block">
                        <span class="block text-[11px] font-bold uppercase tracking-wide text-gray-500 mb-1">From</span>
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                            class="w-full border border-gray-300 rounded-full px-3 py-2 text-sm focus:outline-none focus:border-red-500">
                    </label>
                    <label class="block">
                        <span class="block text-[11px] font-bold uppercase tracking-wide text-gray-500 mb-1">To</span>
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                            class="w-full border border-gray-300 rounded-full px-3 py-2 text-sm focus:outline-none focus:border-red-500">
                    </label>
                </div>
            </div>
        </form>

        <div class="bg-white border rounded-xl overflow-hidden shadow-lg">
            <table class="w-full text-left text-sm">
                <thead class="grc-red text-white uppercase text-xs font-bold">
                    <tr>
                        <th class="px-4 py-3">ID</th>
                        <th class="px-4 py-3">Student Name</th>
                        <th class="px-4 py-3">Violation Type</th>
                        <th class="px-4 py-3">Date & Time</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($violations as $violation)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-bold text-red-700">{{ $violation->violation_code }}</td>
                            <td class="px-4 py-3 font-semibold">
                                {{ $violation->student->full_name ?? $violation->student->name }}</td>
                            <td class="px-4 py-3">{{ $violation->violation_type }}</td>
                            <td class="px-4 py-3 font-bold">{{ $violation->occurred_at?->format('M d, Y h:i A') ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-3">
                                <span
                                    class="inline-block px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wide {{ $violation->status === 'resolved' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ ucfirst($violation->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                @if ($violation->student)
                                    <a href="{{ route('admin.violations.history', $violation->student->id) }}"
                                        class="text-xs bg-gray-200 px-3 py-1.5 rounded-full font-bold hover:bg-gray-300">View
                                        History</a>
                                @else
                                    <span class="text-xs text-gray-400 font-bold">No Student</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-400">No violations recorded.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $violations->links() }}</div>
    </div>
</body>

</html>