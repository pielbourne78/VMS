<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply Consequences - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .bg-maroon { background-color: #A31D1D; }
        .bg-maroon-dark { background-color: #801515; }
    </style>
</head>

<body class="bg-gray-200 antialiased h-screen flex flex-col overflow-hidden">

    <!-- Top Navigation Header -->
    <header class="bg-maroon text-white px-6 py-3 flex items-center justify-between shadow-md z-20 flex-shrink-0 border-b border-red-900">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full border-2 border-white flex items-center justify-center font-bold text-xs bg-white text-red-800 shadow">
                GRC
            </div>
            <div>
                <h1 class="text-xs font-bold leading-none tracking-wide">Global</h1>
                <h2 class="text-xs font-bold leading-tight tracking-wide">Reciprocal Colleges</h2>
            </div>
        </div>

        <nav class="flex items-center space-x-12 text-sm font-bold tracking-wider">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-red-200 transition">DASHBOARD</a>
            <span class="opacity-80">VIOLATION MONITORING</span>
            <span class="opacity-80">REPORT</span>
        </nav>

        <div class="flex items-center">
            <div class="flex items-center space-x-2 border-2 border-white px-4 py-1.5 rounded-full text-xs font-bold">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                    <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path>
                </svg>
                <span>PROFILE</span>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <div class="flex flex-1 overflow-hidden relative">

        <!-- Left Sidebar -->
        <aside class="bg-maroon text-white w-64 flex flex-col justify-between py-6 shadow-xl z-10 flex-shrink-0">
            <div>
                <!-- User Profile Box -->
                <div class="flex flex-col items-center pb-6 px-4">
                    <div class="w-20 h-20 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 mb-2 border-2 border-white shadow overflow-hidden">
                        <svg class="w-12 h-12 fill-current text-gray-500" viewBox="0 0 20 20">
                            <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-sm tracking-wide uppercase text-center">
                        {{ Auth::user()->name ?? 'GAB BALTAZAR' }}
                    </h3>
                    <span class="text-xs font-semibold tracking-wider text-red-200 uppercase">ADMIN</span>
                </div>

                <!-- Navigation Menu -->
                <nav class="mt-4 space-y-1 font-semibold text-sm">
                    <span class="block py-3 px-6 opacity-70">Record Violation</span>
                    <span class="block py-3 px-6 opacity-70">Track violation History</span>
                    <a href="{{ route('admin.apply-consequences') }}" class="block py-3 px-6 bg-maroon-dark font-bold tracking-wide transition border-l-4 border-white">
                        Apply Consequences
                    </a>
                </nav>
            </div>

            <!-- Logout Button -->
            <div class="px-8 mb-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <input type="hidden" name="login_as" value="admin">
                    <button type="submit" class="w-full bg-red-800 hover:bg-red-900 text-white font-bold py-2 px-4 rounded-full text-xs uppercase tracking-wider transition border border-red-700 shadow">
                        LOG OUT
                    </button>
                </form>
            </div>
        </aside>

        <!-- Right Main Content Area (Apply Consequences) -->
        <main class="flex-1 bg-gray-100 p-8 overflow-y-auto">
            
            <h2 class="text-2xl font-black text-gray-800 tracking-tight mb-6 uppercase">CONSEQUENCES</h2>

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg font-semibold text-sm max-w-4xl">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Red Border Box Container -->
            <div class="bg-blue-50/50 border-4 border-red-700 rounded-sm shadow-md max-w-4xl overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b-2 border-red-700 text-gray-800 bg-blue-100/60">
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
                                    {{ $violation->user->full_name ?? $violation->user->name ?? 'Student Name' }}
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
                                        <button type="submit" class="bg-lime-500 hover:bg-lime-600 text-black font-extrabold px-5 py-1 rounded-full text-xs shadow transition">
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

        </main>
    </div>

</body>
</html>