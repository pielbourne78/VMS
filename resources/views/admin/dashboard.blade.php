<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Global Reciprocal Colleges</title>
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
    </style>
</head>

<body class="bg-gray-100 antialiased flex flex-col h-screen overflow-hidden">

    <!-- ================= Top Navbar ================= -->
    <header class="header-gradient text-white px-6 py-4 shadow-md flex items-center justify-between z-20 flex-shrink-0">
        <!-- Logo and College Name -->
        <div class="flex items-center gap-4">
            <img src="/images/logo.jpg" alt="GRC Logo"
                class="h-12 w-12 object-contain bg-white rounded-full p-1 shadow">
            <div>
                <h1 class="text-xs font-bold uppercase tracking-wider opacity-90 leading-tight">Global</h1>
                <h2 class="text-sm font-extrabold uppercase tracking-tight leading-tight">Reciprocal Colleges</h2>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex items-center gap-6 text-sm font-bold">
            <a href="{{ route('admin.dashboard') }}"
                class="bg-white/20 px-5 py-2 rounded-full shadow-inner tracking-wide border border-white/30">DASHBOARD</a>
            <a href="#" class="hover:text-red-200 transition tracking-wide">VIOLATION MONITORING</a>
            <a href="#" class="hover:text-red-200 transition tracking-wide">REPORT</a>
        </nav>

        <!-- Right Side Actions (Bell & Profile) -->
        <div class="flex items-center gap-5">
            <!-- Notification Bell -->
            <div class="bg-red-900/40 p-2.5 rounded-full cursor-pointer hover:bg-red-900/70 transition shadow-inner">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                    </path>
                </svg>
            </div>
            <!-- Profile Pill -->
            <div
                class="flex items-center gap-3 bg-white text-grc-red px-4 py-1.5 rounded-full font-bold cursor-pointer shadow-md hover:bg-gray-50 transition">
                <img src="{{ Auth::user()->profile_photo_url }}" alt="Profile"
                    class="w-7 h-7 rounded-full border-2 border-grc-red object-cover"
                    onerror="this.onerror=null; this.src='https://raw.githubusercontent.com/carlvilla/resources/main/student-avatar.png';">
                <span class="tracking-tight text-xs">PROFILE</span>
            </div>
        </div>
    </header>

    <!-- ================= Main Wrapper Layout ================= -->
    <div class="flex flex-1 overflow-hidden">

        <!-- Sidebar -->
        <aside class="grc-red text-white w-72 flex flex-col py-6 shadow-2xl z-10 flex-shrink-0 relative">

            <!-- Hamburger Menu Icon at Top-Left of Sidebar -->
            <div class="px-6 mb-6 cursor-pointer">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
            </div>

            <!-- Admin Avatar & Info -->
            <div class="px-6 flex flex-col items-center mb-8">
                <div
                    class="w-24 h-24 rounded-full bg-gray-300 overflow-hidden shadow-lg border-4 border-white mb-3 flex items-center justify-center">
                    <img src="https://raw.githubusercontent.com/carlvilla/resources/main/student-avatar.png"
                        alt="Admin Avatar" class="w-full h-full object-cover">
                </div>
                <h3 class="text-lg font-black uppercase tracking-wider text-center">
                    {{ Auth::user()->name ?? 'GABRIELA' }}
                </h3>
                <p class="text-xs font-bold tracking-widest opacity-90 uppercase">ADMIN</p>
            </div>

            <!-- Sidebar Navigation Menu -->
            <nav class="flex flex-col space-y-1 font-bold text-sm">
                <a href="#" class="px-6 py-3.5 bg-red-900/50 border-l-4 border-white tracking-wide transition">Record
                    Violation</a>
                <a href="{{ route('admin.violations.index') }}" class="px-6 py-3.5 hover:bg-red-900/30 transition tracking-wide">Track violation History</a>
                <a href="#" class="px-6 py-3.5 hover:bg-red-900/30 transition tracking-wide">Apply Consequences</a>
            </nav>

            <!-- Collapse / Arrow Toggle Circle Button -->
            <div
                class="absolute right-[-14px] top-64 bg-gray-200 text-gray-700 w-7 h-7 rounded-full flex items-center justify-center shadow-md cursor-pointer border border-gray-300 font-bold hover:bg-white transition">
                &lt;
            </div>

            <!-- Logout Button -->
            <div class="mt-auto px-6">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <input type="hidden" name="login_as" value="admin">
                    <button type="submit"
                        class="w-full bg-white text-grc-red font-black text-center py-2.5 rounded-full shadow-md hover:bg-gray-100 transition duration-150 text-sm tracking-widest">
                        LOG OUT
                    </button>
                </form>
            </div>
        </aside>

        <!-- ================= Right Main Content Area ================= -->
        <main class="flex-1 flex flex-col bg-white overflow-y-auto px-10 py-8">

            <!-- Page Title -->
            <h2 class="text-2xl font-black text-gray-900 tracking-tight mb-8">ADMIN DASHBOARD</h2>

            <!-- Cards Stack -->
            <div class="space-y-6 max-w-5xl">

                <!-- Card 1: Total Violation -->
                <div
                    class="card-red-gradient text-white rounded-xl p-5 shadow-lg flex items-center justify-between border border-red-600">
                    <div class="flex items-center gap-6">
                        <div class="bg-white/10 p-3 rounded-lg border border-white/20">
                            <!-- Document & Magnifier Icon -->
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-black tracking-wider">TOTAL VIOLATION</h3>
                    </div>
                    <a href="#"
                        class="text-white text-sm font-bold tracking-tight hover:underline flex items-center gap-1">
                        View details &gt;
                    </a>
                </div>

                <!-- Card 2: Active Cases -->
                <div
                    class="card-red-gradient text-white rounded-xl p-5 shadow-lg flex items-center justify-between border border-red-600">
                    <div class="flex items-center gap-6">
                        <div class="bg-white/10 p-3 rounded-lg border border-white/20">
                            <!-- User Circle Icon -->
                            <svg class="w-10 h-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.654 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-black tracking-wider">ACTIVE CASES</h3>
                    </div>
                    <a href="#"
                        class="text-white text-sm font-bold tracking-tight hover:underline flex items-center gap-1">
                        View details &gt;
                    </a>
                </div>

                <!-- Card 3: Pending Alert -->
                <div
                    class="card-red-gradient text-white rounded-xl p-5 shadow-lg flex items-center justify-between border border-red-600">
                    <div class="flex items-center gap-6">
                        <div class="bg-white/10 p-3 rounded-lg border border-white/20">
                            <!-- Clock / Pending Icon -->
                            <svg class="w-10 h-10 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-black tracking-wider">PENDING ALERT</h3>
                    </div>
                    <a href="#"
                        class="text-white text-sm font-bold tracking-tight hover:underline flex items-center gap-1">
                        View details &gt;
                    </a>
                </div>

                <!-- Card 4: Resolve Cases -->
                <div
                    class="card-red-gradient text-white rounded-xl p-5 shadow-lg flex items-center justify-between border border-red-600">
                    <div class="flex items-center gap-6">
                        <div class="bg-white/10 p-3 rounded-lg border border-white/20">
                            <!-- Resolve / Check Thumb Icon -->
                            <svg class="w-10 h-10 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-black tracking-wider">RESOLVE CASES</h3>
                    </div>
                    <a href="#"
                        class="text-white text-sm font-bold tracking-tight hover:underline flex items-center gap-1">
                        View details &gt;
                    </a>
                </div>

            </div>
        </main>
    </div>

</body>

</html>