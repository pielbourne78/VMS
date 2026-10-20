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

        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #f8f8f8 0%, #f1f5f9 100%);
            color: var(--grc-text);
        }

        .grc-red {
            background-color: var(--grc-red);
        }

        .text-grc-red {
            color: var(--grc-red);
        }

        .border-grc-red {
            border-color: var(--grc-red);
        }

        .header-gradient {
            background: linear-gradient(90deg, var(--grc-red) 0%, var(--grc-red-dark) 100%);
        }

        .brand-overlay {
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.85rem;
            min-width: 220px;
            padding: 0.5rem 0.85rem;
            border-radius: 9999px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.25);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.12);
        }

        .brand-overlay img {
            width: 2.8rem;
            height: 2.8rem;
            border-radius: 9999px;
            object-fit: cover;
            background: #fff;
            padding: 0.2rem;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
        }

        .brand-overlay-title {
            line-height: 1.05;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            font-size: 0.62rem;
        }

        .header-nav a {
            padding: 0.5rem 1.2rem;
            border-radius: 9999px;
            transition: all 0.2s ease;
        }

        .header-nav a:hover {
            background: rgba(255, 255, 255, 0.08);
        }

        .stat-btn-gradient {
            background: linear-gradient(90deg, var(--grc-red) 0%, #E32B3E 100%);
        }

        .penalty-btn-gradient {
            background: linear-gradient(90deg, #D91C2D 0%, #FC4558 100%);
        }

        .soft-glow {
            box-shadow: 0 14px 30px rgba(191, 30, 46, 0.12);
        }

        .card-hover {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 30px rgba(15, 23, 42, 0.08);
        }

        @media (max-width: 1024px) {
            .dashboard-shell {
                flex-direction: column;
            }

            .sidebar-panel {
                width: 100%;
            }
        }

        @media (max-width: 768px) {
            .header-nav {
                gap: 0.75rem;
                flex-wrap: wrap;
            }

            .main-grid {
                grid-template-columns: 1fr;
            }

            .main-grid>section {
                grid-column: span 12;
            }
        }
    </style>
    @stack('styles')
</head>

<body class="antialiased min-h-screen overflow-x-hidden">
    @php
        $currentUser = Auth::check() ? Auth::user() : null;
        $userName = $currentUser?->name ?? 'Guest';
        $userPhoto = $currentUser?->profile_photo_url ?? 'https://raw.githubusercontent.com/carlvilla/resources/main/student-avatar.png';
        $notificationsEnabled = \Illuminate\Support\Facades\Schema::hasTable('notifications') && $currentUser;
        $allNotifications = $notificationsEnabled ? $currentUser->notifications()->latest()->limit(10)->get() : collect();
        $notifications = $allNotifications->filter(function ($notification) {
            $data = $notification->data ?? [];
            $type = $data['type'] ?? null;
            $title = $data['title'] ?? '';
            $message = $data['message'] ?? '';

            if (in_array($type, ['migration_warning', 'sanction_warning'], true)) {
                return true;
            }

            return str_contains(strtolower((string) $title), 'sanction warning')
                || str_contains(strtolower((string) $message), 'sanctions charged')
                || str_contains(strtolower((string) $message), 'sanction warning');
        });
        $unreadCount = $notifications->whereNull('read_at')->count();
    @endphp

    <div class="dashboard-shell relative min-h-screen">
        <header
            class="header-gradient text-white px-6 py-4 shadow-md flex items-center justify-between z-20 flex-shrink-0">
            <!-- Logo and College Name -->
            <div class="flex items-center gap-4">
                <button id="sidebarOpenBtn" onclick="toggleSidebar()"
                    class="text-white text-2xl focus:outline-none hover:opacity-80 transition cursor-pointer rounded-full bg-white/10 p-2">
                    ☰
                </button>
                <img src="{{ asset('images/logo.jpg') }}" alt="GRC Logo"
                    class="h-12 w-12 object-contain bg-white rounded-full p-1 shadow-lg ring-2 ring-white/50">
                <div>
                    <h1 class="text-xs font-bold uppercase tracking-wider opacity-90 leading-tight">Global</h1>
                    <h2 class="text-sm font-extrabold uppercase tracking-tight leading-tight">Reciprocal Colleges</h2>
                </div>
            </div>

            <nav
                class="hidden md:flex flex-1 items-center justify-center flex-wrap gap-2 text-base font-bold tracking-tight header-nav">
                <a href="{{ route('dashboard') }}"
                    class="{{ request()->routeIs('dashboard') ? 'stat-btn-gradient text-white shadow-inner' : 'hover:text-red-200' }} tracking-wide uppercase">
                    Dashboard
                </a>
                <a href="{{ route('violation.monitoring') }}"
                    class="{{ request()->routeIs('violation.monitoring') ? 'stat-btn-gradient text-white shadow-inner' : 'hover:text-red-200' }} tracking-wide uppercase">
                    Violation Monitoring
                </a>
                <a href="{{ route('report') }}"
                    class="{{ request()->routeIs('report') ? 'stat-btn-gradient text-white shadow-inner' : 'hover:text-red-200' }} tracking-wide uppercase">
                    Report
                </a>
            </nav>

            <div class="flex items-center gap-4">
                <span class="text-white font-bold text-sm tracking-wide uppercase hidden xl:inline">
                    {{ $userName }}
                </span>

                <div class="relative">
                    <button id="notifBellButton" type="button"
                        class="bg-red-900/40 p-2.5 rounded-full cursor-pointer hover:bg-red-900/70 transition shadow-inner focus:outline-none flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
                        </svg>
                        @if($unreadCount > 0)
                            <span
                                class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 flex items-center justify-center text-[10px] font-bold bg-red-500 text-white rounded-full border-2 border-white">{{ $unreadCount }}</span>
                        @endif
                    </button>

                    <div id="notifDropdown"
                        class="hidden absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-xl border border-gray-200 z-50 text-gray-800 overflow-hidden">
                        <div class="px-4 py-3 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                            <h4 class="text-sm font-extrabold text-grc-red uppercase tracking-wider">Notifications
                            </h4>
                            <span
                                class="text-xs bg-red-100 text-grc-red font-bold px-2 py-0.5 rounded-full">{{ $unreadCount }}</span>
                        </div>
                        <div class="max-h-64 overflow-y-auto divide-y divide-gray-100">
                            @if($notifications->isNotEmpty())
                            @foreach($notifications as $notification)
                            @php($data = $notification->data ?? [])
                                <a href="{{ $data['url'] ?? '#' }}" data-notification-id="{{ $notification->id }}"
                                    class="block p-3.5 hover:bg-gray-50 transition {{ is_null($notification->read_at) ? 'bg-red-50/60' : 'bg-white' }}"
                                    @if(!empty($data['url']))
                                    onclick="markNotificationRead('{{ $notification->id }}', this);" @else
                                    onclick="markNotificationRead('{{ $notification->id }}', this); return false;" @endif>
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="text-xs font-bold text-gray-800 uppercase">
                                                {{ $data['title'] ?? 'Notification' }}
                                            </p>
                                            <p class="text-[11px] text-gray-500 mt-0.5">
                                                {{ $data['message'] ?? 'You have a new update.' }}
                                            </p>
                                        </div>
                                        @if(is_null($notification->read_at))
                                            <span class="w-2.5 h-2.5 rounded-full bg-red-500 mt-1.5 flex-shrink-0"></span>
                                        @endif
                                    </div>
                                </a>
                                @endforeach
                            @else
                            <div class="p-6 text-center text-gray-400 text-xs">🔔 No new notifications available.
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <button id="profileMenuButton" type="button"
                        class="flex items-center gap-3 bg-white text-grc-red px-4 py-1.5 rounded-full font-bold cursor-pointer shadow-md hover:bg-gray-50 transition focus:outline-none">
                        <img src="{{ $userPhoto }}" alt="Profile"
                            class="w-8 h-8 rounded-full border-2 border-grc-red object-cover"
                            onerror="this.onerror=null; this.src='https://raw.githubusercontent.com/carlvilla/resources/main/student-avatar.png';">
                        <span class="tracking-tight text-sm">PROFILE</span>
                        <svg id="profileMenuChevron" class="w-4 h-4 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>

                    <div id="profileDropdown"
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

    <div class="flex min-h-screen">
        @include('components.sidebar')

        <div class="flex-1 flex flex-col bg-white">
            @if(session('error'))
                <div
                    class="mx-8 mt-4 rounded-lg bg-red-100 border border-red-300 text-red-800 px-4 py-3 text-sm font-semibold">
                    {{ session('error') }}
                </div>
            @endif
            @if(session('success'))
                <div
                    class="mx-8 mt-4 rounded-lg bg-green-100 border border-green-300 text-green-800 px-4 py-3 text-sm font-semibold">
                    {{ session('success') }}
                </div>
            @endif
            <!-- Page-specific content -->
            @yield('content')

        </div>
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

        function markNotificationRead(notificationId, element) {
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

                const badge = document.querySelector('#notifBellButton .absolute');
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