<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Global Reciprocal Colleges - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Montserrat', sans-serif; }
        /* Custom colors from screenshot */
        .grc-red { background-color: #BF1E2E; }
        .text-grc-red { color: #BF1E2E; }
        .border-grc-red { border-color: #BF1E2E; }
        /* Gradient for header and main buttons */
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
    <div class="flex-1 flex flex-col bg-white">
        <!-- Header Navigation -->
        <header class="header-gradient text-white px-8 py-6 shadow-lg flex items-center justify-between border-b-4 border-grc-red flex-shrink-0">
            <nav class="flex items-center gap-6 text-lg font-semibold tracking-tight">
                <!-- Hamburger Toggle Button -->
                <button onclick="toggleSidebar()" class="text-white text-2xl focus:outline-none hover:opacity-80 transition cursor-pointer pr-2">
                    ☰
                </button>
                <a href="{{ route('dashboard') }}" class="stat-btn-gradient text-white px-6 py-2 rounded-full shadow-inner">DASHBOARD</a>
                <a href="#" class="hover:text-red-200 transition">VIOLATION MONITORING</a>
                <a href="{{ route('report') }}" class="hover:text-red-200 transition">REPORT</a>
            </nav>
            <div class="flex items-center gap-6">
                <!-- Notification Bell -->
                <svg class="w-8 h-8 cursor-pointer hover:text-red-200 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                <!-- Profile Dropdown -->
                <div class="flex items-center gap-3 bg-white text-grc-red px-4 py-1 rounded-full font-bold cursor-pointer shadow-md hover:bg-gray-100 transition">
                     <img src="https://raw.githubusercontent.com/carlvilla/resources/main/student-avatar.png" alt="Profile" class="w-8 h-8 rounded-full border-2 border-grc-red">
                     <span class="tracking-tight">PROFILE</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>
        </header>

        <!-- Content Body (Grid Layout) -->
        <main class="flex-1 p-8 grid grid-cols-12 gap-8 overflow-y-auto">
            
            <!-- ** Left Column: Violation List ** -->
            <section class="col-span-7 space-y-6">
                <!-- Example Row 1 -->
                <div class="grid grid-cols-5 items-center bg-white border-b border-gray-200 py-3 text-center font-bold">
                    <div class="col-span-3 text-left text-grc-red text-lg pl-4">
                        NOT WEARING PROPER UNIFORM
                    </div>
                    <div class="flex justify-center">
                        <img src="https://raw.githubusercontent.com/carlvilla/resources/main/alert-yellow.png" alt="Minor" class="h-8 w-8">
                    </div>
                    <div class="flex flex-col items-center gap-1">
                        <span class="text-grc-red text-sm tracking-tight">PENALTY 1</span>
                        <button class="bg-grc-red text-white text-xs px-4 py-1 rounded-full font-semibold shadow">PENALTY QUIZ</button>
                    </div>
                </div>

                <!-- Example Row 2 -->
                <div class="grid grid-cols-5 items-center bg-white border-b border-gray-200 py-3 text-center font-bold">
                    <div class="col-span-3 text-left text-grc-red text-lg pl-4">
                        VANDALISM
                    </div>
                    <div class="flex justify-center">
                        <img src="https://raw.githubusercontent.com/carlvilla/resources/main/alert-red.png" alt="Major" class="h-8 w-8">
                    </div>
                    <div class="flex flex-col items-center gap-1">
                        <span class="text-grc-red text-sm tracking-tight">PENALTY 2</span>
                        <button class="bg-grc-red text-white text-xs px-4 py-1 rounded-full font-semibold shadow">PENALTY QUIZ</button>
                    </div>
                </div>

                <!-- Example Row 3 -->
                <div class="grid grid-cols-5 items-center bg-white border-b border-gray-200 py-3 text-center font-bold">
                    <div class="col-span-3 text-left text-grc-red text-lg pl-4">
                        BULLYING
                    </div>
                    <div class="flex justify-center">
                        <img src="https://raw.githubusercontent.com/carlvilla/resources/main/alert-red.png" alt="Major" class="h-8 w-8">
                    </div>
                    <div class="flex flex-col items-center gap-1">
                        <span class="text-grc-red text-sm tracking-tight">PENALTY 3</span>
                        <button class="bg-grc-red text-white text-xs px-4 py-1 rounded-full font-semibold shadow">PENALTY QUIZ</button>
                    </div>
                </div>

                <!-- Example Row 4 -->
                <div class="grid grid-cols-5 items-center bg-white border-b border-gray-200 py-3 text-center font-bold">
                    <div class="col-span-3 text-left text-grc-red text-lg pl-4">
                        COLORED HAIR
                    </div>
                    <div class="flex justify-center">
                        <img src="https://raw.githubusercontent.com/carlvilla/resources/main/alert-yellow.png" alt="Minor" class="h-8 w-8">
                    </div>
                    <div class="flex flex-col items-center gap-1">
                        <span class="text-grc-red text-sm tracking-tight">PENALTY 4</span>
                        <button class="bg-grc-red text-white text-xs px-4 py-1 rounded-full font-semibold shadow">PENALTY QUIZ</button>
                    </div>
                </div>

                <!-- Example Row 5 -->
                <div class="grid grid-cols-5 items-center bg-white border-b border-gray-200 py-3 text-center font-bold">
                    <div class="col-span-3 text-left text-grc-red text-lg pl-4">
                        THIEF
                    </div>
                    <div class="flex justify-center">
                        <img src="https://raw.githubusercontent.com/carlvilla/resources/main/alert-red.png" alt="Major" class="h-8 w-8">
                    </div>
                    <div class="flex flex-col items-center gap-1">
                        <span class="text-grc-red text-sm tracking-tight">PENALTY 5</span>
                        <button class="bg-grc-red text-white text-xs px-4 py-1 rounded-full font-semibold shadow">PENALTY QUIZ</button>
                    </div>
                </div>

                <!-- Example Row 6 -->
                <div class="grid grid-cols-5 items-center bg-white border-b border-gray-200 py-3 text-center font-bold">
                    <div class="col-span-3 text-left text-grc-red text-lg pl-4">
                        SMOKING
                    </div>
                    <div class="flex justify-center">
                        <img src="https://raw.githubusercontent.com/carlvilla/resources/main/alert-red.png" alt="Major" class="h-8 w-8">
                    </div>
                    <div class="flex flex-col items-center gap-1">
                        <span class="text-grc-red text-sm tracking-tight">PENALTY 6</span>
                        <span class="bg-grc-red text-white text-xs px-4 py-1 rounded-full font-semibold shadow">PENDING</span>
                    </div>
                </div>
            </section>

            <!-- ** Right Column: Statistics Cards ** -->
            <section class="col-span-5 space-y-8">
                <!-- Statistics Header -->
                <div class="flex items-center gap-6">
                    <img src="https://raw.githubusercontent.com/carlvilla/resources/main/alert-yellow.png" alt="Total" class="w-16 h-16">
                    <div>
                        <h3 class="text-xl font-bold text-gray-600">TOTAL VIOLATION</h3>
                        <p class="text-grc-red text-6xl font-black tracking-tighter">5</p>
                    </div>
                </div>

                <!-- Minor Offense Card -->
                <div class="flex gap-5 bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
                    <img src="https://raw.githubusercontent.com/carlvilla/resources/main/alert-yellow.png" alt="Minor" class="w-16 h-16 flex-shrink-0 mt-2">
                    <div>
                        <h3 class="text-grc-red text-3xl font-black tracking-tight mb-3">MINOR OFFENSE</h3>
                        <p class="text-gray-700 text-base leading-relaxed font-medium">
                            A minor offense is a small-scale infraction that disrupts order or violates administrative procedures but does not cause significant harm or safety risks. These are often viewed as "correctable behaviors" rather than malicious acts.
                        </p>
                    </div>
                </div>

                <!-- Major Offense Card -->
                <div class="flex gap-5 bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
                    <img src="https://raw.githubusercontent.com/carlvilla/resources/main/alert-red.png" alt="Major" class="w-16 h-16 flex-shrink-0 mt-2">
                    <div>
                        <h3 class="text-grc-red text-3xl font-black tracking-tight mb-3">MAJOR OFFENSE</h3>
                        <p class="text-gray-700 text-base leading-relaxed font-medium">
                            A major offense is a serious violation that directly threatens the integrity, safety, or legal standing of an organization. These actions often involve a breach of trust, ethics, or the law.
                        </p>
                    </div>
                </div>
            </section>
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