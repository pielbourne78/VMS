<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Global Reciprocal Colleges - @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --grc-red: #BF1E2E;
            --grc-red-dark: #A01A28;
            --grc-red-soft: #f86a7f;
            --grc-text: #1F2937;
        }
        body { font-family: 'Montserrat', sans-serif; background: linear-gradient(135deg, #f8f8f8 0%, #f1f5f9 100%); color: var(--grc-text); }
        .grc-red { background-color: var(--grc-red); }
        .text-grc-red { color: var(--grc-red); }
        .border-grc-red { border-color: var(--grc-red); }
        .header-gradient { background: linear-gradient(90deg, var(--grc-red) 0%, var(--grc-red-dark) 100%); }
        .stat-btn-gradient { background: linear-gradient(90deg, var(--grc-red) 0%, #E32B3E 100%); }
        .penalty-btn-gradient { background: linear-gradient(90deg, #D91C2D 0%, #FC4558 100%); }
        .soft-glow { box-shadow: 0 14px 30px rgba(191, 30, 46, 0.12); }
        .card-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .card-hover:hover { transform: translateY(-2px); box-shadow: 0 16px 30px rgba(15, 23, 42, 0.08); }

        @media (max-width: 1024px) {
            .dashboard-shell { flex-direction: column; }
            .sidebar-panel { width: 100%; }
        }
        @media (max-width: 768px) {
            .header-nav { gap: 0.75rem; flex-wrap: wrap; }
            .main-grid { grid-template-columns: 1fr; }
            .main-grid > section { grid-column: span 12; }
        }
    </style>
    @stack('styles')
</head>

<body class="antialiased min-h-screen overflow-x-hidden">

    <div class="dashboard-shell flex min-h-screen">

        <!-- Reusable Sidebar Component -->
        @include('components.sidebar')

        <!-- ================= Main Content ================= -->
        <div class="flex-1 flex flex-col bg-white">

            <!-- Header Navigation -->
            <header class="header-gradient text-white px-8 py-5 shadow-lg flex items-center justify-between border-b-4 border-grc-red flex-shrink-0">
                <nav class="flex items-center gap-6 text-base font-semibold tracking-tight header-nav">
                    <button id="sidebarOpenBtn" onclick="toggleSidebar()" class="text-white text-2xl focus:outline-none hover:opacity-80 transition cursor-pointer pr-2">
                        ☰
                    </button>
                    <a href="{{ route('dashboard') }}"
                        class="{{ request()->routeIs('dashboard') ? 'stat-btn-gradient text-white px-6 py-2.5 rounded-full shadow-inner' : 'hover:text-red-200 transition' }} tracking-wide">
                        DASHBOARD
                    </a>
                    <a href="{{ route('violation.monitoring') }}"
                        class="{{ request()->routeIs('violation.monitoring') ? 'stat-btn-gradient text-white px-6 py-2.5 rounded-full shadow-inner' : 'hover:text-red-200 transition' }} tracking-wide">
                        VIOLATION MONITORING
                    </a>
                    <a href="{{ route('report') }}"
                        class="{{ request()->routeIs('report') ? 'stat-btn-gradient text-white px-6 py-2.5 rounded-full shadow-inner' : 'hover:text-red-200 transition' }} tracking-wide">
                        REPORT
                    </a>
                </nav>

                <div class="flex items-center gap-5">
                    <!-- Notification Bell Wrapper -->
                    <div class="relative">
                        <button id="notifBellButton" type="button" class="bg-red-900/40 p-2.5 rounded-full cursor-pointer hover:bg-red-900/70 transition shadow-inner focus:outline-none flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                </path>
                            </svg>
                            @if(isset($violations) && count($violations) > 0)
                                <span class="absolute top-0 right-0 w-3 h-3 bg-red-500 rounded-full border-2 border-white"></span>
                            @endif
                        </button>

                        <!-- Notification Dropdown Menu -->
                        <div id="notifDropdown" class="hidden absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-xl border border-gray-200 z-50 text-gray-800 overflow-hidden">
                            <div class="px-4 py-3 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                                <h4 class="text-sm font-extrabold text-grc-red uppercase tracking-wider">Notifications</h4>
                                <span class="text-xs bg-red-100 text-grc-red font-bold px-2 py-0.5 rounded-full">
                                    {{ isset($violations) ? count($violations) : 0 }}
                                </span>
                            </div>
                            <div class="max-h-64 overflow-y-auto divide-y divide-gray-100">
                                @if(isset($violations) && count($violations) > 0)
                                    @foreach($violations as $violation)
                                        <div class="p-3.5 hover:bg-gray-50 transition cursor-pointer">
                                            <p class="text-xs font-bold text-gray-800 uppercase">{{ $violation->title ?? $violation['title'] }}</p>
                                            <p class="text-[11px] text-gray-500 mt-0.5">Please address your penalty accordingly.</p>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="p-6 text-center text-gray-400 text-xs">
                                        🔔 No new notifications available.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Profile Dropdown -->
                    <div class="relative">
                        <button id="profileMenuButton" type="button"
                            class="flex items-center gap-3 bg-white text-grc-red px-4 py-1.5 rounded-full font-bold cursor-pointer shadow-md hover:bg-gray-50 transition focus:outline-none">
                            <img src="{{ Auth::user()->profile_photo_url }}" alt="Profile"
                                class="w-8 h-8 rounded-full border-2 border-grc-red object-cover"
                                onerror="this.onerror=null; this.src='https://raw.githubusercontent.com/carlvilla/resources/main/student-avatar.png';">
                            <span class="tracking-tight text-sm">PROFILE</span>
                            <svg id="profileMenuChevron" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="profileDropdown" class="hidden absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-xl border border-gray-200 z-50 text-gray-800 overflow-hidden">
                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition text-sm font-semibold">
                                <svg class="w-5 h-5 text-grc-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-3 px-4 py-3 hover:bg-red-50 transition text-sm font-semibold text-grc-red border-t border-gray-100">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page-specific content -->
            @yield('content')

        </div>
    </div>

    <!-- Shared Scripts -->
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const openBtn = document.getElementById('sidebarOpenBtn');
        const closeArrow = document.getElementById('sidebarCloseArrow');

        sidebar.classList.toggle('sidebar-closed');

        const isOpen = !sidebar.classList.contains('sidebar-closed');

        if (isOpen) {
            // Sidebar opening: hide hamburger, show arrow, pulse
            openBtn.classList.add('hidden');
            closeArrow.classList.remove('hidden');

            sidebar.classList.remove('sidebar-emphasize');
            void sidebar.offsetWidth;
            sidebar.classList.add('sidebar-emphasize');
        } else {
            // Sidebar closing: show hamburger, hide arrow
            openBtn.classList.remove('hidden');
            closeArrow.classList.add('hidden');
        }
    }

    // Notification Bell Dropdown
    document.addEventListener('DOMContentLoaded', function () {
        const bellBtn = document.getElementById('notifBellButton');
        const dropdown = document.getElementById('notifDropdown');

        if (bellBtn && dropdown) {
            bellBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                dropdown.classList.toggle('hidden');
            });

            document.addEventListener('click', function (e) {
                if (!dropdown.contains(e.target) && !bellBtn.contains(e.target)) {
                    dropdown.classList.add('hidden');
                }
            });
        }
    });

    // Profile Dropdown
    document.addEventListener('DOMContentLoaded', function () {
        const profileBtn = document.getElementById('profileMenuButton');
        const profileDropdown = document.getElementById('profileDropdown');
        const profileChevron = document.getElementById('profileMenuChevron');

        if (profileBtn && profileDropdown) {
            profileBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                profileDropdown.classList.toggle('hidden');
                profileChevron.classList.toggle('rotate-180');
            });

            document.addEventListener('click', function (e) {
                if (!profileDropdown.contains(e.target) && !profileBtn.contains(e.target)) {
                    profileDropdown.classList.add('hidden');
                    profileChevron.classList.remove('rotate-180');
                }
            });
        }
    });
</script>
    @stack('scripts')
</body>

</html>