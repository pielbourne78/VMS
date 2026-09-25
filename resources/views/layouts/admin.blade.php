<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - @yield('title', 'Dashboard')</title>
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

        .card-red-gradient {
            background: linear-gradient(90deg, #C51D2C 0%, #D92334 100%);
        }

        #adminSidebar {
            transition: all 0.3s ease;
        }

        #adminSidebar.admin-sidebar-collapsed {
            width: 0 !important;
            min-width: 0 !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            opacity: 0;
            overflow: hidden;
        }
    </style>
    @stack('styles')

</head>

<body class="bg-gray-100 antialiased flex flex-col h-screen overflow-hidden">

    <!-- ================= Top Navbar ================= -->
    <header class="header-gradient text-white px-6 py-4 shadow-md flex items-center justify-between z-20 flex-shrink-0">
        <!-- Logo and College Name -->
        <div class="flex items-center gap-4">
            <button id="adminSidebarOpenBtn" onclick="toggleAdminSidebar()"
                class="text-white text-2xl focus:outline-none hover:opacity-80 transition cursor-pointer">
                ☰
            </button>
            <img src="{{ asset('images/logo.jpg') }}" alt="GRC Logo"
                class="h-12 w-12 object-contain bg-white rounded-full p-1 shadow">
            <div>
                <h1 class="text-xs font-bold uppercase tracking-wider opacity-90 leading-tight">Global</h1>
                <h2 class="text-sm font-extrabold uppercase tracking-tight leading-tight">Reciprocal Colleges</h2>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex items-center gap-6 text-sm font-bold">
            <a href="{{ route('admin.dashboard') }}"
                class="{{ request()->routeIs('admin.dashboard') ? 'bg-white/20 px-5 py-2 rounded-full shadow-inner border border-white/30' : 'hover:text-red-200 transition' }} tracking-wide">
                DASHBOARD
            </a>
            <a href="{{ route('admin.violations.recent') }}"
                class="{{ request()->routeIs('admin.violations.recent') ? 'bg-white/20 px-5 py-2 rounded-full shadow-inner border border-white/30' : 'hover:text-red-200 transition' }} tracking-wide">
                VIOLATION MONITORING
            </a>
            <a href="{{ route('admin.appeals') }}"
                class="{{ request()->routeIs('admin.appeals') ? 'bg-white/20 px-5 py-2 rounded-full shadow-inner border border-white/30' : 'hover:text-red-200 transition' }} tracking-wide">
                APPEALS
            </a>
            <a href="{{ route('admin.report') }}" class="hover:text-red-200 transition tracking-wide">REPORT</a>
        </nav>

        <!-- Right Side Actions (Bell & Profile) -->
        <div class="flex items-center gap-5">
            <!-- Notification Bell -->
            <div class="relative">
                <button id="adminNotifBellButton" type="button"
                    class="bg-red-900/40 p-2.5 rounded-full cursor-pointer hover:bg-red-900/70 transition shadow-inner focus:outline-none flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                        </path>
                    </svg>
                    @php($adminNotificationsEnabled = \Illuminate\Support\Facades\Schema::hasTable('notifications'))
                    @php($adminUnreadCount = $adminNotificationsEnabled ? Auth::user()->unreadNotifications()->count() : 0)
                    @if($adminUnreadCount > 0)
                        <span
                            class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 flex items-center justify-center text-[10px] font-bold bg-red-500 text-white rounded-full border-2 border-white">{{ $adminUnreadCount }}</span>
                    @endif
                </button>

                <div id="adminNotifDropdown"
                    class="hidden absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-xl border border-gray-200 z-50 text-gray-800 overflow-hidden">
                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                        <h4 class="text-sm font-extrabold text-grc-red uppercase tracking-wider">Notifications</h4>
                        <span
                            class="text-xs bg-red-100 text-grc-red font-bold px-2 py-0.5 rounded-full">{{ $adminUnreadCount }}</span>
                    </div>
                    <div class="max-h-64 overflow-y-auto divide-y divide-gray-100">
                        @php($adminNotifications = $adminNotificationsEnabled ? Auth::user()->notifications()->latest()->limit(10)->get() : collect())
                        @if($adminNotifications->isNotEmpty())
                        @foreach($adminNotifications as $notification)
                        @php($data = $notification->data ?? [])
                            <a href="{{ $data['url'] ?? '#' }}" data-notification-id="{{ $notification->id }}"
                                class="block p-3.5 hover:bg-gray-50 transition {{ is_null($notification->read_at) ? 'bg-red-50/60' : 'bg-white' }}"
                                @if(!empty($data['url']))
                                onclick="markAdminNotificationRead('{{ $notification->id }}', this);" @else
                                onclick="markAdminNotificationRead('{{ $notification->id }}', this); return false;" @endif>
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="text-xs font-bold text-gray-800 uppercase">
                                            {{ $data['title'] ?? 'Notification' }}</p>
                                        <p class="text-[11px] text-gray-500 mt-0.5">
                                            {{ $data['message'] ?? 'You have a new update.' }}</p>
                                    </div>
                                    @if(is_null($notification->read_at))
                                        <span class="w-2.5 h-2.5 rounded-full bg-red-500 mt-1.5 flex-shrink-0"></span>
                                    @endif
                                </div>
                            </a>
                            @endforeach
                        @else
                        <div class="p-6 text-center text-gray-400 text-xs">
                            🔔 No new notifications available.
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- Profile Pill -->
            <!-- Profile Dropdown -->
            <div class="relative">
                <button id="adminProfileMenuButton" type="button"
                    class="flex items-center gap-3 bg-white text-grc-red px-4 py-1.5 rounded-full font-bold cursor-pointer shadow-md hover:bg-gray-50 transition focus:outline-none">
                    <img src="{{ Auth::user()->profile_photo_url }}" alt="Profile"
                        class="w-7 h-7 rounded-full border-2 border-grc-red object-cover"
                        onerror="this.onerror=null; this.src='https://raw.githubusercontent.com/carlvilla/resources/main/student-avatar.png';">
                    <span class="tracking-tight text-xs">PROFILE</span>
                    <svg id="adminProfileMenuChevron" class="w-4 h-4 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div id="adminProfileDropdown"
                    class="hidden absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-xl border border-gray-200 z-50 text-gray-800 overflow-hidden">
                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition text-sm font-semibold">
                        <svg class="w-5 h-5 text-grc-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                        Edit Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center gap-3 px-4 py-3 hover:bg-red-50 transition text-sm font-semibold text-grc-red border-t border-gray-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                </path>
                            </svg>
                            Log Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- ================= Main Wrapper Layout ================= -->
    <div class="flex flex-1 overflow-hidden">

        <!-- Reusable Admin Sidebar -->
        @include('components.admin-sidebar')

        <!-- ================= Right Main Content Area ================= -->
        <main class="flex-1 flex flex-col bg-white overflow-y-auto px-10 py-8">
            @yield('content')
        </main>
    </div>

    <script>
        function toggleAdminSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const openBtn = document.getElementById('adminSidebarOpenBtn');
            const closeArrow = document.getElementById('adminSidebarCloseArrow');

            sidebar.classList.toggle('admin-sidebar-collapsed');

            const isOpen = !sidebar.classList.contains('admin-sidebar-collapsed');

            if (isOpen) {
                openBtn.classList.add('hidden');
                closeArrow.classList.remove('hidden');
            } else {
                openBtn.classList.remove('hidden');
                closeArrow.classList.add('hidden');
            }
        }

        function markAdminNotificationRead(notificationId, element) {
            fetch(`/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({})
            }).then(response => response.json()).then(() => {
                if (element) {
                    element.classList.remove('bg-red-50/60');
                    element.classList.add('bg-white');
                    const dot = element.querySelector('span.w-2\\.5');
                    if (dot) dot.remove();
                }

                const badge = document.querySelector('#adminNotifBellButton .absolute');
                if (badge) {
                    const currentCount = Number(badge.textContent.trim());
                    if (currentCount > 1) {
                        badge.textContent = currentCount - 1;
                    } else {
                        badge.remove();
                    }
                }
            }).catch(() => { });
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const profileBtn = document.getElementById('adminProfileMenuButton');
            const profileDropdown = document.getElementById('adminProfileDropdown');
            const profileChevron = document.getElementById('adminProfileMenuChevron');

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

            const adminBellBtn = document.getElementById('adminNotifBellButton');
            const adminDropdown = document.getElementById('adminNotifDropdown');

            if (adminBellBtn && adminDropdown) {
                adminBellBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    adminDropdown.classList.toggle('hidden');
                });

                document.addEventListener('click', function (e) {
                    if (!adminDropdown.contains(e.target) && !adminBellBtn.contains(e.target)) {
                        adminDropdown.classList.add('hidden');
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>

</html>