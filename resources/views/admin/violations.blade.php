<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Violation Monitoring - Global Reciprocal Colleges</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Playfair+Display:wght@500;600&display=swap" rel="stylesheet">
    <style>
        :root { --grc-red: #BF1E2E; --field: #d9d9d9; }
        body { font-family: 'Montserrat', sans-serif; }
        .header-red { background: linear-gradient(90deg, #BF1E2E 0%, #90121D 100%); }
        .screen-title { background: #d3090d; }
        .sidebar-red { background: #BF1E2E; }
        .field { background: var(--field); }
        .serif { font-family: 'Playfair Display', Georgia, serif; }
        .screen { display: none; }
        .screen.active { display: block; }
        .sidebar-link.active { background: var(--grc-red); }
        .admin-sidebar { transition: width .25s ease, min-width .25s ease, padding .25s ease, opacity .25s ease; }
        .admin-sidebar.collapsed { width: 0; min-width: 0; padding-left: 0; padding-right: 0; }
        .admin-sidebar.collapsed > :not(#adminSidebarToggle) { visibility: hidden; }
        .admin-sidebar.collapsed #adminSidebarToggle { right: -22px; }
        @media (max-width: 800px) {
            .header-links { gap: 12px; font-size: 12px; }
            .sidebar { width: 190px; }
            .form-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 560px) {
            body { overflow: auto; }
            .app-body { flex-direction: column; overflow: visible; }
            .sidebar { width: 100%; min-height: auto; }
            .header-links a:not(.monitor-link) { display: none; }
        }
    </style>
</head>

    <body class="bg-gray-100 text-gray-900 flex h-screen min-h-0 flex-col overflow-hidden">
    <header class="header-red flex items-center justify-between px-6 py-4 text-white shadow-md z-20 shrink-0">
        <div class="flex items-center gap-3 shrink-0">
            <img src="/images/logo.jpg" alt="Global Reciprocal Colleges" class="h-12 w-12 rounded-full object-contain bg-white p-1 shadow">
            <div class="text-xs font-bold uppercase tracking-wider leading-tight opacity-90">Global<br><span class="text-sm font-extrabold tracking-tight">Reciprocal Colleges</span></div>
        </div>
        <nav class="header-links flex items-center gap-6 text-sm font-bold whitespace-nowrap">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-red-200 transition tracking-wide">DASHBOARD</a>
            <a href="{{ route('admin.violation.monitoring') }}" class="monitor-link rounded-full bg-white/20 border border-white/30 px-5 py-2 shadow-inner tracking-wide">VIOLATION MONITORING</a>
            <a href="{{ route('admin.report') }}" class="hover:text-red-200 transition tracking-wide">REPORT</a>
        </nav>
        <div class="flex items-center gap-5 shrink-0">
            <div class="relative">
            <button id="adminNotifButton" type="button" aria-label="Notifications" aria-expanded="false" class="bg-red-900/40 p-2.5 rounded-full cursor-pointer hover:bg-red-900/70 transition shadow-inner">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                @if ($notifications->isNotEmpty())
                    <span class="absolute -right-1 -top-1 flex h-4 min-w-4 items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-bold text-white">{{ $notifications->count() }}</span>
                @endif
            </button>
            <div id="adminNotifDropdown" class="absolute right-0 z-50 mt-3 hidden w-80 overflow-hidden rounded-xl border border-gray-200 bg-white text-gray-800 shadow-xl">
                <div class="flex items-center justify-between border-b border-gray-100 bg-gray-50 px-4 py-3">
                    <h3 class="text-sm font-bold uppercase tracking-wide text-red-700">Notifications</h3>
                    <span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-bold text-red-700">{{ $notifications->count() }}</span>
                </div>
                <div class="max-h-72 overflow-y-auto">
                    @forelse ($notifications as $notification)
                        <div class="border-b border-gray-100 px-4 py-3 last:border-0">
                            <p class="text-sm font-bold">{{ $notification->violator_name }}: {{ $notification->violation_type }}</p>
                            <p class="mt-1 text-xs text-gray-500">{{ $notification->violation_datetime->format('M j, Y g:i A') }} · {{ ucfirst($notification->status) }}</p>
                        </div>
                    @empty
                        <p class="px-4 py-8 text-center text-sm text-gray-400">No new notifications.</p>
                    @endforelse
                </div>
            </div>
            </div>
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 rounded-full bg-white px-4 py-1.5 font-bold text-red-700 shadow-md">
                <img src="{{ Auth::user()->profile_photo_url }}" alt="Profile" class="h-7 w-7 rounded-full border-2 border-red-700 object-cover" onerror="this.onerror=null; this.src='https://raw.githubusercontent.com/carlvilla/resources/main/student-avatar.png';">
                <span class="text-xs tracking-tight">PROFILE</span>
            </a>
        </div>
    </header>

    <div class="app-body flex min-h-0 flex-1 overflow-hidden">
        <aside id="adminSidebar" class="admin-sidebar sidebar sidebar-red relative flex w-72 shrink-0 flex-col py-6 text-white shadow-2xl">
            <div class="flex flex-col items-center px-6 mb-8">
                <img src="{{ Auth::user()->profile_photo_url }}" alt="Admin" class="h-24 w-24 rounded-full border-4 border-white object-cover shadow-lg" onerror="this.onerror=null; this.src='https://raw.githubusercontent.com/carlvilla/resources/main/student-avatar.png';">
                <h2 class="mt-3 text-center text-lg font-black uppercase tracking-wider">{{ Auth::user()->name ?? 'GAB BALTAZAR' }}</h2>
                <span class="text-xs font-bold tracking-widest opacity-90">ADMIN</span>
            </div>
            <nav class="flex flex-col space-y-1 text-sm font-bold">
                <button type="button" data-screen="record" class="sidebar-link active w-full px-6 py-3.5 text-left tracking-wide" onclick="showScreen('record')">Record Violation</button>
                <button type="button" data-screen="history" class="sidebar-link w-full px-6 py-3.5 text-left tracking-wide" onclick="showScreen('history')">Track Violation History</button>
                <button type="button" data-screen="history" class="sidebar-link w-full px-6 py-3.5 text-left tracking-wide" onclick="showScreen('history')">Apply Consequences</button>
            </nav>
            <button id="adminSidebarToggle" type="button" aria-label="Collapse sidebar" aria-expanded="true" class="absolute right-[-22px] top-64 flex h-11 w-11 items-center justify-center rounded-full bg-white text-red-700 shadow-md transition hover:bg-gray-100 z-20">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <div class="mt-auto px-6">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <input type="hidden" name="login_as" value="admin">
                    <button type="submit" class="w-full rounded-full bg-white px-4 py-2.5 text-sm font-black tracking-widest text-red-700 shadow-md">LOG OUT</button>
                </form>
            </div>
        </aside>

        <main class="flex min-h-0 flex-1 flex-col overflow-y-auto bg-white">
            @if (session('success'))
                <div class="mx-auto mt-4 max-w-4xl rounded bg-green-100 px-4 py-3 text-sm font-semibold text-green-800">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="mx-auto mt-4 max-w-4xl rounded bg-red-100 px-4 py-3 text-sm text-red-800">Please complete all required fields.</div>
            @endif

            <section id="screen-record" class="screen active">
                <div class="screen-title px-7 py-7 text-3xl font-black tracking-tight text-white">VIOLATOR</div>
                <form method="POST" action="{{ route('admin.violations.store') }}" class="mx-auto max-w-5xl px-10 py-16 pb-10">
                    @csrf
                    <div class="form-grid grid grid-cols-2 gap-x-20 gap-y-5">
                        <label class="block text-base">student ID number
                            <select name="user_id" id="student-select" required class="field mt-1 block h-11 w-full border-0 px-5 text-base outline-none">
                                <option value="">STUDENT ID</option>
                                @foreach ($students as $student)
                                    <option value="{{ $student->id }}" data-student-id="{{ $student->student_id }}" @selected(old('user_id') == $student->id)>{{ $student->student_id }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label class="block text-base">Violation Type
                            <select name="violation_type" required class="field mt-1 block h-11 w-full border-0 px-5 text-base outline-none">
                                <option value="">Select violation type</option>
                                @foreach ($violationTypes as $type)<option @selected(old('violation_type') === $type)>{{ $type }}</option>@endforeach
                            </select>
                        </label>
                        <label class="block text-base">Date and Time
                            <input type="datetime-local" name="occurred_at" required class="field mt-1 block h-11 w-full border-0 px-5 text-base outline-none" value="{{ old('occurred_at') }}">
                        </label>
                        <label class="block text-base">Location
                            <select name="location" required class="field mt-1 block h-11 w-full border-0 px-5 text-base outline-none">
                                <option value="">Select Location</option>
                                @foreach ($locations as $location)<option @selected(old('location') === $location)>{{ $location }}</option>@endforeach
                            </select>
                        </label>
                        <label class="block text-base">Description
                            <textarea name="description" required rows="3" placeholder="Enter description of the violation" class="field mt-1 block w-full resize-none border-0 px-5 py-4 text-base outline-none">{{ old('description') }}</textarea>
                        </label>
                        <div class="row-span-2">
                            <span class="block text-base">ADMIN SEND NOTIFICATION</span>
                            <label class="field mt-1 flex h-11 items-center justify-between px-5 text-base text-gray-500">
                                <span>NOTIFICATION ALERT</span><input type="checkbox" name="notification_alert" value="1" @checked(old('notification_alert')) class="h-5 w-5 accent-red-700">
                            </label>
                            <label class="field serif mt-3 flex min-h-[106px] items-center justify-center px-5 text-center text-xl">
                                <input type="checkbox" name="student_notification" value="1" @checked(old('student_notification')) class="mr-3 h-4 w-4 accent-red-700">
                                ADMIN ALERT<br>NOTIFICATION FOR<br>STUDENT
                            </label>
                        </div>
                        <label class="block text-base">Reported by
                            <input type="text" readonly value="{{ Auth::user()->name }}" class="field mt-1 block h-11 w-full border-0 px-5 text-base outline-none">
                        </label>
                    </div>
                    <div class="mt-12 flex justify-end gap-3 pb-2">
                        <button type="reset" class="rounded-xl bg-red-600 px-6 py-2 text-white">Cancel</button>
                        <button type="submit" class="rounded-xl bg-black px-7 py-2 text-white">Next</button>
                    </div>
                </form>
            </section>

        </main>
    </div>

    <script>
        const adminSidebar = document.getElementById('adminSidebar');
        const adminSidebarToggle = document.getElementById('adminSidebarToggle');

        adminSidebarToggle.addEventListener('click', function () {
            const isCollapsed = adminSidebar.classList.toggle('collapsed');
            adminSidebarToggle.setAttribute('aria-expanded', String(!isCollapsed));
            adminSidebarToggle.setAttribute('aria-label', isCollapsed ? 'Expand sidebar' : 'Collapse sidebar');
        });

        const adminNotifButton = document.getElementById('adminNotifButton');
        const adminNotifDropdown = document.getElementById('adminNotifDropdown');

        adminNotifButton.addEventListener('click', function () {
            const isHidden = adminNotifDropdown.classList.toggle('hidden');
            adminNotifButton.setAttribute('aria-expanded', String(!isHidden));
        });

        document.addEventListener('click', function (event) {
            if (!adminNotifButton.parentElement.contains(event.target)) {
                adminNotifDropdown.classList.add('hidden');
                adminNotifButton.setAttribute('aria-expanded', 'false');
            }
        });

        function showScreen(screen) {
            document.querySelectorAll('.screen').forEach(function (element) { element.classList.toggle('active', element.id === 'screen-' + screen); });
            document.querySelectorAll('.sidebar-link').forEach(function (element) { element.classList.toggle('active', element.dataset.screen === screen); });
        }
        document.getElementById('student-select').addEventListener('change', function () {
            const option = this.options[this.selectedIndex];
            this.options[0].textContent = option.dataset.studentId || 'STUDENT ID';
        });
    </script>
</body>

</html>