<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Global Reciprocal Colleges - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --grc-red: #BF1E2E;
            --grc-red-dark: #A01A28;
            --grc-red-soft: #FCE8EB;
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

            .main-grid > section {
                grid-column: span 12;
            }
        }
    </style>
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
                    <button onclick="toggleSidebar()" class="text-white text-2xl focus:outline-none hover:opacity-80 transition cursor-pointer pr-2">
                        ☰
                    </button>
                    <a href="{{ route('dashboard') }}" class="stat-btn-gradient text-white px-6 py-2.5 rounded-full shadow-inner tracking-wide">DASHBOARD</a>
                    <a href="{{ route('violation.monitoring') }}" class="hover:text-red-200 transition tracking-wide">VIOLATION MONITORING</a>
                    <a href="#" class="hover:text-red-200 transition tracking-wide">REPORT</a>
                </nav>
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
                    <div class="flex items-center gap-3 bg-white text-grc-red px-4 py-1.5 rounded-full font-bold cursor-pointer shadow-md hover:bg-gray-50 transition">
                        <img src="https://raw.githubusercontent.com/carlvilla/resources/main/student-avatar.png"
                            alt="Profile" class="w-8 h-8 rounded-full border-2 border-grc-red object-cover">
                        <span class="tracking-tight text-sm">PROFILE</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
            </header>

            <!-- Content Body (Grid Layout) -->
            <main class="flex-1 p-8 grid grid-cols-12 gap-8 overflow-y-auto bg-gray-50/50 main-grid">

                @if(isset($violations) && count($violations) > 0)
                    <!-- ** Left Column: Active Violation List ** -->
                    <section class="col-span-7 space-y-4">
                        @foreach($violations as $index => $violation)
                            <div class="grid grid-cols-5 items-center bg-white border border-gray-200/80 rounded-xl p-3 text-center font-bold shadow-sm">
                                <div class="col-span-3 text-left text-grc-red text-base pl-2 uppercase">
                                    {{ $violation->title ?? $violation['title'] }}
                                </div>
                                <div class="flex justify-center">
                                    @if(($violation->severity ?? $violation['severity']) === 'major')
                                        <img src="https://raw.githubusercontent.com/carlvilla/resources/main/alert-red.png" alt="Major" class="h-8 w-8">
                                    @else
                                        <img src="https://raw.githubusercontent.com/carlvilla/resources/main/alert-yellow.png" alt="Minor" class="h-8 w-8">
                                    @endif
                                </div>
                                <div class="flex flex-col items-center gap-1">
                                    <span class="text-grc-red text-xs tracking-tight">PENALTY {{ $index + 1 }}</span>
                                    @if(($violation->status ?? $violation['status']) === 'pending')
                                        <button class="bg-grc-red text-white text-xs px-4 py-1 rounded-full font-semibold shadow hover:bg-red-700">PENDING</button>
                                    @else
                                        <button class="penalty-btn-gradient text-white text-xs px-4 py-1 rounded-full font-semibold shadow">PENALTY QUIZ</button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </section>

                    <!-- ** Right Column: Violation Breakdown Cards ** -->
                    <section class="col-span-5 space-y-6">
                        <div class="flex items-center gap-6 bg-white p-6 rounded-2xl shadow-sm border border-gray-200/80">
                            <img src="https://raw.githubusercontent.com/carlvilla/resources/main/alert-yellow.png" alt="Total" class="w-16 h-16">
                            <div>
                                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">TOTAL VIOLATIONS</h3>
                                <p class="text-grc-red text-5xl font-black tracking-tighter">{{ count($violations) }}</p>
                            </div>
                        </div>

                        <div class="flex gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-200/80">
                            <img src="https://raw.githubusercontent.com/carlvilla/resources/main/alert-yellow.png" alt="Minor" class="w-12 h-12 flex-shrink-0 mt-1">
                            <div>
                                <h3 class="text-grc-red text-xl font-extrabold tracking-tight mb-2">MINOR OFFENSE</h3>
                                <p class="text-gray-600 text-xs leading-relaxed font-medium">
                                    Small-scale infractions that disrupt order but do not cause significant harm or safety risks. Often treated as correctable behaviors.
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-200/80">
                            <img src="https://raw.githubusercontent.com/carlvilla/resources/main/alert-red.png" alt="Major" class="w-12 h-12 flex-shrink-0 mt-1">
                            <div>
                                <h3 class="text-grc-red text-xl font-extrabold tracking-tight mb-2">MAJOR OFFENSE</h3>
                                <p class="text-gray-600 text-xs leading-relaxed font-medium">
                                    Serious violations threatening the integrity, safety, or legal standing of the institution involving breaches of trust, ethics, or law.
                                </p>
                            </div>
                        </div>
                    </section>
                @else
                    <!-- ** Left Column: Empty Clean Record State ** -->
                    <section class="col-span-7 flex flex-col">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 p-8 flex-1 flex flex-col items-center justify-center text-center">
                            <div class="w-20 h-20 bg-green-50 text-green-500 rounded-full flex items-center justify-center text-4xl mb-4 shadow-inner">
                                🎉
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">No Violations Recorded</h3>
                            <p class="text-gray-500 text-sm max-w-md leading-relaxed">
                                Fantastic job! Your record is currently clean with zero reported violations. Keep up the great conduct and discipline inside the campus.
                            </p>
                        </div>
                    </section>

                    <!-- ** Right Column: Clean Record Statistics & Reminder ** -->
                    <section class="col-span-5 space-y-6">
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200/80 flex items-center justify-between">
                            <div>
                                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">TOTAL VIOLATIONS</h3>
                                <p class="text-grc-red text-5xl font-black tracking-tighter">0</p>
                            </div>
                            <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center text-grc-red shadow-inner font-bold text-xl">
                                🛡️
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-red-50 to-white p-6 rounded-2xl shadow-sm border border-red-100">
                            <div class="flex items-center gap-3 mb-3">
                                <span class="text-2xl">💡</span>
                                <h3 class="text-grc-red text-lg font-bold tracking-tight">Student Code Reminder</h3>
                            </div>
                            <p class="text-gray-600 text-sm leading-relaxed font-medium">
                                Global Reciprocal Colleges values a safe, orderly, and respectful learning environment. Always wear your complete school uniform, maintain proper grooming, and respect fellow students and faculty members.
                            </p>
                        </div>
                    </section>
                @endif

            </main>
        </div>
    </div>

    <!-- JavaScript for Sidebar Toggle -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            if (sidebar.style.width === '0px' || sidebar.style.width === '') {
                sidebar.style.width = '0px';
                sidebar.style.minWidth = '0px';
                sidebar.style.paddingLeft = '0px';
                sidebar.style.paddingRight = '0px';
                sidebar.style.opacity = '0';
            } else {
                sidebar.style.width = '320px';
                sidebar.style.minWidth = '320px';
                sidebar.style.paddingLeft = '';
                sidebar.style.paddingRight = '';
                sidebar.style.opacity = '1';
            }
        }
        
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('sidebar-closed');
    }

    </script>
</body>

</html>