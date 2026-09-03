<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report - Global Reciprocal Colleges</title>
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

        .text-grc-red {
            color: #BF1E2E;
        }

        .border-grc-red {
            border-color: #BF1E2E;
        }

        .header-gradient {
            background: linear-gradient(90deg, #BF1E2E 0%, #90121D 100%);
        }
    </style>
</head>

<body class="grc-red antialiased flex flex-col h-screen overflow-hidden">

    <!-- ================= Top Navbar ================= -->
    <header class="text-white px-6 py-4 flex items-center justify-between z-20 flex-shrink-0">
        <!-- Logo and College Name -->
        <div class="flex items-center gap-4">
            <img src="/images/logo.jpg" alt="GRC Logo"
                class="h-12 w-12 object-contain bg-white rounded-full p-1 shadow"
                onerror="this.onerror=null; this.src='https://raw.githubusercontent.com/carlvilla/resources/main/grc-logo.png';">
            <div>
                <h1 class="text-xs font-bold uppercase tracking-wider opacity-90 leading-tight">Global</h1>
                <h2 class="text-sm font-extrabold uppercase tracking-tight leading-tight">Reciprocal Colleges</h2>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex items-center gap-6 text-sm font-bold">
            <a href="{{ route('admin.dashboard') }}"
                class="hover:text-red-200 transition tracking-wide px-5 py-2">DASHBOARD</a>
            <a href="#" class="hover:text-red-200 transition tracking-wide px-5 py-2">VIOLATION MONITORING</a>
            <a href="{{ route('admin.report') }}"
                class="bg-white/20 px-5 py-2 rounded-full shadow-inner tracking-wide border border-white/30">REPORT</a> 
        </nav>

        <!-- Right Side Actions (Bell & Profile) -->
        <div class="flex items-center gap-5">
            <!-- Notification Bell -->
            <div class="bg-red-950/40 p-2.5 rounded-full cursor-pointer hover:bg-red-950/70 transition shadow-inner">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                    </path>
                </svg>
            </div>
            <!-- Profile Pill -->
            <div
                class="flex items-center gap-3 bg-white text-grc-red px-4 py-1.5 rounded-full font-bold cursor-pointer shadow-md hover:bg-gray-50 transition">
                <img src="{{ Auth::user()->profile_photo_url ?? 'https://raw.githubusercontent.com/carlvilla/resources/main/student-avatar.png' }}" alt="Profile"
                    class="w-7 h-7 rounded-full border-2 border-grc-red object-cover"
                    onerror="this.onerror=null; this.src='https://raw.githubusercontent.com/carlvilla/resources/main/student-avatar.png';">
                <span class="tracking-tight text-xs">PROFILE</span>
            </div>
        </div>
    </header>

    <!-- ================= Main Wrapper Layout ================= -->
    <main class="flex-1 flex flex-col px-6 pb-6 overflow-hidden">

        <!-- Wide Rounded White Container Box -->
        <div class="bg-white rounded-3xl shadow-2xl flex-1 flex flex-col p-8 w-full overflow-hidden border border-gray-100">
            
            <!-- Table Headers -->
            <div class="grid grid-cols-3 font-black text-lg text-black px-8 mb-6 tracking-wide flex-shrink-0">
                <div>STUDENT NAME:</div>
                <div class="text-center">VIOLATION:</div>
                <div class="text-right pr-4">CASE STATUS:</div>
            </div>

            <!-- Scrollable Violation Rows Container -->
            <div class="space-y-4 overflow-y-auto pr-2 flex-1">
                @forelse($violations ?? [] as $violation)
                    <div class="grid grid-cols-3 items-center bg-gray-200/70 rounded-full py-4 px-8 shadow-sm border border-gray-300/50">
                        <!-- Student Name -->
                        <div class="font-bold text-gray-800 text-base tracking-wide uppercase">
                            {{ $violation->user->name ?? 'N/A' }}
                        </div>

                        <!-- Violation Type -->
                        <div class="text-center font-black text-gray-900 text-base tracking-wider uppercase">
                            {{ $violation->violation_type ?? $violation->offense_name ?? $violation->title ?? 'N/A' }}
                        </div>

                        <!-- Case Status with Icon -->
                        <div class="flex items-center justify-end gap-3 font-extrabold text-sm tracking-wider">
                            @php $status = strtolower($violation->status ?? 'pending'); @endphp
                            
                            @if($status === 'resolved')
                                <span class="text-gray-900">RESOLVED</span>
                                <span class="w-6 h-6 rounded-full bg-green-500 text-white flex items-center justify-center text-xs font-bold shadow">✓</span>
                            @else
                                <span class="text-gray-900 uppercase">PENDING</span>
                                <div class="w-6 h-6 rounded-full bg-red-600 text-white flex items-center justify-center text-[10px] font-black shadow">
                                    <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <!-- Placeholder data rows -->
                    <div class="grid grid-cols-3 items-center bg-gray-200/70 rounded-full py-4 px-8 shadow-sm border border-gray-300/50">
                        <div class="font-bold text-gray-800 text-base tracking-wide uppercase">Gab Baltazar</div>
                        <div class="text-center font-black text-gray-900 text-base tracking-wider uppercase">Nagma-madjong</div>
                        <div class="flex items-center justify-end gap-3 font-extrabold text-sm tracking-wider">
                            <span class="text-gray-900 uppercase">Pending</span>
                            <div class="w-6 h-6 rounded-full bg-red-600 text-white flex items-center justify-center text-[10px] font-black shadow">
                                <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 items-center bg-gray-200/70 rounded-full py-4 px-8 shadow-sm border border-gray-300/50">
                        <div class="font-bold text-gray-800 text-base tracking-wide uppercase">Bob</div>
                        <div class="text-center font-black text-gray-900 text-base tracking-wider uppercase">Bullying</div>
                        <div class="flex items-center justify-end gap-3 font-extrabold text-sm tracking-wider">
                            <span class="text-gray-900">Resolved</span>
                            <span class="w-6 h-6 rounded-full bg-green-500 text-white flex items-center justify-center text-xs font-bold shadow">✓</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 items-center bg-gray-200/70 rounded-full py-4 px-8 shadow-sm border border-gray-300/50">
                        <div class="font-bold text-gray-800 text-base tracking-wide uppercase">Josh Agustin</div>
                        <div class="text-center font-black text-gray-900 text-base tracking-wider uppercase">Vandalism</div>
                        <div class="flex items-center justify-end gap-3 font-extrabold text-sm tracking-wider">
                            <span class="text-gray-900 uppercase">Pending</span>
                            <div class="w-6 h-6 rounded-full bg-red-600 text-white flex items-center justify-center text-[10px] font-black shadow">
                                <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

        </div>
    </main>

</body>

</html>