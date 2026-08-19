<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Violation History & Report - VMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Montserrat', sans-serif; }
        .grc-red { background-color: #BF1E2E; }
        .text-grc-red { color: #BF1E2E; }
        .border-grc-red { border-color: #BF1E2E; }
        .header-gradient { background: linear-gradient(90deg, #BF1E2E 0%, #A01A28 100%); }
        .stat-btn-gradient { background: linear-gradient(90deg, #BF1E2E 0%, #E32B3E 100%); }
    </style>
</head>
<body class="bg-gray-100 antialiased flex min-h-screen overflow-x-hidden">

    <!-- ================= Sidebar ================= -->
    <aside id="sidebar" class="grc-red text-white w-80 flex flex-col py-6 shadow-xl z-10 flex-shrink-0 transition-all duration-300">
        <!-- College Logo & Name -->
        <div class="px-6 flex items-center gap-3 border-b border-red-700 pb-6 mb-6">
            <img src="https://raw.githubusercontent.com/carlvilla/resources/main/grc-logo.png" alt="GRC Logo" class="h-12 w-12">
            <div>
                <h1 class="text-lg font-bold leading-tight">Global<br>Reciprocal<br>Colleges</h1>
            </div>
        </div>

        <!-- Student Profile Section -->
        <div class="px-6 flex flex-col items-center mb-10">
            <div class="relative mb-4">
                <img src="https://raw.githubusercontent.com/carlvilla/resources/main/student-avatar.png" alt="Student Avatar" class="w-28 h-28 rounded-full border-4 border-white shadow-lg">
                <div class="absolute bottom-1 right-1 bg-green-500 w-6 h-6 rounded-full border-4 border-white"></div>
            </div>
            <h2 class="text-2xl font-extrabold uppercase tracking-wide text-center">{{ Auth::user()->name }}</h2>
            <p class="text-sm opacity-90 font-medium">STUDENT</p>
        </div>

        <!-- Student Info List -->
        <div class="px-6 space-y-5 text-base font-semibold">
            <div class="flex items-start gap-3">
            <span class="mt-1.5 text-lg">•</span>
            <p>{{ Auth::user()->course }}</p>
            </div>
            <div class="flex items-start gap-3">
            <span class="mt-1.5 text-lg">•</span>
            <p>{{ Auth::user()->section }}</p>
            </div>
            <div class="flex items-start gap-3">
            <span class="mt-1.5 text-lg">•</span>
            <p>{{ Auth::user()->year_level }}</p>
            </div>
            <div class="flex items-start gap-3">
            <span class="mt-1.5 text-lg">•</span>
            <a href="{{ route('code.of.discipline') }}" class="border-b border-white pb-1 cursor-pointer hover:opacity-80 transition-opacity">
                ARTICLES VI CODE OF DISCIPLINE
            </a>
            </div>
        </div>

        <!-- Logout Button -->
        <div class="mt-auto px-6">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full bg-white text-grc-red font-bold text-center py-3 rounded-full shadow-md hover:bg-gray-100 transition duration-150 text-lg tracking-wider">
                    LOG OUT
                </button>
            </form>
        </div>
    </aside>

    <!-- ================= Main Content ================= -->
    <div class="flex-1 flex flex-col bg-grc-red">
        <!-- Header Navigation -->
        <header class="header-gradient text-white px-8 py-6 shadow-lg flex items-center justify-between border-b-4 border-red-800 flex-shrink-0">
            <nav class="flex items-center gap-6 text-lg font-semibold tracking-tight">
                <!-- Hamburger Toggle Button -->
                <button onclick="toggleSidebar()" class="text-white text-2xl focus:outline-none hover:opacity-80 transition cursor-pointer pr-2">
                    ☰
                </button>
                <a href="{{ route('dashboard') }}" class="hover:text-red-200 transition">DASHBOARD</a>
                <a href="#" class="hover:text-red-200 transition">VIOLATION MONITORING</a>
                <a href="{{ route('report') }}" class="stat-btn-gradient text-white px-6 py-2 rounded-full shadow-inner">REPORT</a>
            </nav>
            <div class="flex items-center gap-6">
                <!-- Notification Bell with badge -->
                <div class="relative cursor-pointer hover:text-red-200 transition">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <span class="absolute -top-1 -right-1 bg-white text-grc-red text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center shadow">1</span>
                </div>
                <!-- Profile Dropdown -->
                <div class="flex items-center gap-3 bg-white text-grc-red px-4 py-1 rounded-full font-bold cursor-pointer shadow-md hover:bg-gray-100 transition">
                     <img src="https://raw.githubusercontent.com/carlvilla/resources/main/student-avatar.png" alt="Profile" class="w-8 h-8 rounded-full border-2 border-grc-red">
                     <span class="tracking-tight">PROFILE</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>
        </header>

        <!-- Content Body -->
        <main class="flex-1 p-8 flex justify-center items-start overflow-y-auto">
            <div class="w-full max-w-6xl bg-white rounded-3xl p-10 shadow-2xl relative">

                <!-- Section Title -->
                <h2 class="text-grc-red text-2xl font-black mb-8 tracking-wide">Violation history:</h2>

                <!-- Violation Rows -->
                <div class="space-y-6">
                    @forelse($violations ?? [] as $violation)
                        <div class="flex items-center justify-between border-b border-gray-100 pb-6">
                            <span class="text-grc-red font-extrabold text-lg tracking-wide uppercase">
                                {{ $violation->offense_name ?? $violation->title }}
                            </span>
                            <div>
                                @if(strtolower($violation->status) === 'resolved')
                                    <span class="flex items-center gap-2 text-green-600 font-bold text-sm tracking-wide">
                                        <span class="w-5 h-5 rounded-full bg-green-600 text-white flex items-center justify-center text-xs">✓</span> RESOLVED CASE
                                    </span>
                                @elseif(strtolower($violation->status) === 'active')
                                    <span class="flex items-center gap-2 text-red-600 font-bold text-sm tracking-wide">
                                        <span class="w-5 h-5 rounded-full bg-red-600 text-white flex items-center justify-center text-xs font-bold">!</span> ACTIVE CASE
                                    </span>
                                @elseif(strtolower($violation->status) === 'pending')
                                    <span class="flex items-center gap-2 text-amber-500 font-bold text-sm tracking-wide">
                                        <span class="w-5 h-5 rounded-full bg-amber-500 text-white flex items-center justify-center text-xs font-bold">!</span> ACTIVE CASE
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <!-- Row 1: Resolved -->
                        <div class="flex items-center justify-between border-b border-gray-100 pb-6">
                            <span class="text-grc-red font-extrabold text-lg tracking-wide">NOT WEARING PROPER UNIFORM</span>
                            <span class="flex items-center gap-2 text-green-600 font-bold text-sm tracking-wide">
                                <span class="w-5 h-5 rounded-full bg-green-600 text-white flex items-center justify-center text-xs">✓</span> RESOLVED CASE
                            </span>
                        </div>

                        <!-- Row 2: Active Case -->
                        <div class="flex items-center justify-between border-b border-gray-100 pb-6">
                            <span class="text-grc-red font-extrabold text-lg tracking-wide">VANDALISM</span>
                            <span class="flex items-center gap-2 text-red-600 font-bold text-sm tracking-wide">
                                <span class="w-5 h-5 rounded-full bg-red-600 text-white flex items-center justify-center text-xs font-bold">!</span> ACTIVE CASE
                            </span>
                        </div>

                        <!-- Row 3: Active Case -->
                        <div class="flex items-center justify-between border-b border-gray-100 pb-6">
                            <span class="text-grc-red font-extrabold text-lg tracking-wide">BULLYING</span>
                            <span class="flex items-center gap-2 text-red-600 font-bold text-sm tracking-wide">
                                <span class="w-5 h-5 rounded-full bg-red-600 text-white flex items-center justify-center text-xs font-bold">!</span> ACTIVE CASE
                            </span>
                        </div>

                        <!-- Row 4: Active Case (Yellow Minor) -->
                        <div class="flex items-center justify-between border-b border-gray-100 pb-6">
                            <span class="text-grc-red font-extrabold text-lg tracking-wide">COLORED HAIR</span>
                            <span class="flex items-center gap-2 text-amber-500 font-bold text-sm tracking-wide">
                                <span class="w-5 h-5 rounded-full bg-amber-500 text-white flex items-center justify-center text-xs font-bold">!</span> ACTIVE CASE
                            </span>
                        </div>

                        <!-- Row 5: Active Case -->
                        <div class="flex items-center justify-between border-b border-gray-100 pb-6">
                            <span class="text-grc-red font-extrabold text-lg tracking-wide">THIEF</span>
                            <span class="flex items-center gap-2 text-red-600 font-bold text-sm tracking-wide">
                                <span class="w-5 h-5 rounded-full bg-red-600 text-white flex items-center justify-center text-xs font-bold">!</span> ACTIVE CASE
                            </span>
                        </div>

                        <!-- Row 6: Smoking -->
                        <div class="flex items-center justify-between pb-2">
                            <span class="text-grc-red font-extrabold text-lg tracking-wide">SMOKING</span>
                            <span class="flex items-center gap-2 text-red-600 font-bold text-sm tracking-wide">
                                <span class="w-5 h-5 rounded-full bg-red-600 text-white flex items-center justify-center text-xs font-bold">!</span> ACTIVE CASE
                            </span>
                        </div>
                    @endforelse
                </div>

            </div>
        </main>
    </div>

    <!-- JavaScript for Sidebar Toggle -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('hidden');
        }
    </script>
</body>
</html>