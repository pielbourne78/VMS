<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Violation Monitoring - Global Reciprocal Colleges</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Montserrat', sans-serif; }
        .grc-red { background-color: #BF1E2E; }
        .text-grc-red { color: #BF1E2E; }
        .header-gradient { background: linear-gradient(90deg, #BF1E2E 0%, #90121D 100%); }
        .card-red-gradient { background: linear-gradient(90deg, #C51D2C 0%, #D92334 100%); }
        .violation-copy { font-family: Georgia, 'Times New Roman', serif; }
        @media (min-width: 640px) {
            .violation-grid { grid-template-columns: minmax(0, 1.15fr) minmax(0, 1fr) minmax(0, 1.2fr) 150px !important; }
        }
    </style>
</head>

<body class="bg-gray-100 antialiased flex flex-col h-screen overflow-hidden">
    <header class="header-gradient text-white px-6 py-4 shadow-md flex items-center justify-between z-20 flex-shrink-0">
        <div class="flex items-center gap-4">
            <img src="/images/logo.jpg" alt="GRC Logo" class="h-12 w-12 object-contain bg-white rounded-full p-1 shadow">
            <div>
                <h1 class="text-xs font-bold uppercase tracking-wider opacity-90 leading-tight">Global</h1>
                <h2 class="text-sm font-extrabold uppercase tracking-tight leading-tight">Reciprocal Colleges</h2>
            </div>
        </div>
        <nav class="flex items-center gap-6 text-sm font-bold">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-red-200 transition tracking-wide">DASHBOARD</a>
            <a href="{{ route('admin.violation.monitoring') }}" class="bg-white/20 px-5 py-2 rounded-full shadow-inner tracking-wide border border-white/30">VIOLATION MONITORING</a>
            <a href="{{ route('admin.violation.history') }}" class="hover:text-red-200 transition tracking-wide">REPORT</a>
        </nav>
        <div class="flex items-center gap-5">
            <div class="bg-red-900/40 p-2.5 rounded-full shadow-inner" aria-label="Notifications">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
            </div>
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 bg-white text-grc-red px-4 py-1.5 rounded-full font-bold shadow-md hover:bg-gray-50 transition">
                <img src="{{ Auth::user()->profile_photo_url }}" alt="Profile" class="w-7 h-7 rounded-full border-2 border-grc-red object-cover" onerror="this.onerror=null; this.src='https://raw.githubusercontent.com/carlvilla/resources/main/student-avatar.png';">
                <span class="tracking-tight text-xs">PROFILE</span>
            </a>
        </div>
    </header>

    <div class="flex flex-1 overflow-hidden">
        <aside class="grc-red text-white w-72 flex flex-col py-6 shadow-2xl z-10 flex-shrink-0 relative">
            <div class="px-6 mb-6">
                <a href="{{ route('admin.dashboard') }}" aria-label="Back to dashboard" class="text-2xl font-bold">&lt;</a>
            </div>
            <div class="px-6 flex flex-col items-center mb-8">
                <div class="w-24 h-24 rounded-full bg-gray-300 overflow-hidden shadow-lg border-4 border-white mb-3 flex items-center justify-center">
                    <img src="https://raw.githubusercontent.com/carlvilla/resources/main/student-avatar.png" alt="Admin Avatar" class="w-full h-full object-cover">
                </div>
                <h3 class="text-lg font-black uppercase tracking-wider text-center">{{ Auth::user()->name ?? 'GABRIELA' }}</h3>
                <p class="text-xs font-bold tracking-widest opacity-90 uppercase">ADMIN</p>
            </div>
            <nav class="flex flex-col space-y-1 font-bold text-sm">
                <a href="{{ route('admin.violation.record') }}" class="px-6 py-3 hover:bg-red-800/50 transition {{ $section === 'record' ? 'bg-red-800/50 border-l-4 border-white' : '' }}">
                    RECORD VIOLATION
                </a>
                <a href="{{ route('admin.violation.history') }}" class="px-6 py-3 hover:bg-red-800/50 transition {{ $section === 'history' ? 'bg-red-800/50 border-l-4 border-white' : '' }}">
                    TRACK VIOLATION HISTORY
                </a>
                <a href="{{ route('admin.violation.consequences') }}" class="px-6 py-3 hover:bg-red-800/50 transition {{ $section === 'consequences' ? 'bg-red-800/50 border-l-4 border-white' : '' }}">
                    APPLY CONSEQUENCES
                </a>
            </nav>
            <div class="mt-auto px-6">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <input type="hidden" name="login_as" value="admin">
                    <button type="submit" class="w-full bg-white text-grc-red font-black text-center py-2.5 rounded-full shadow-md hover:bg-gray-100 transition duration-150 text-sm tracking-widest">LOG OUT</button>
                </form>
            </div>
        </aside>

        <main class="flex-1 flex flex-col bg-white overflow-y-auto">
            @if ($section === 'overview')
            <h2 class="bg-red-600 text-white text-3xl font-black tracking-tight px-8 py-8 shadow-sm">RECENT VIOLATION</h2>
            <div class="flex-1 px-8 py-10 lg:px-12">
                <div class="max-w-6xl mx-auto space-y-5">
                    <div class="violation-grid grid grid-cols-1 gap-3 px-6 text-sm font-black uppercase tracking-wide text-gray-700 sm:items-center sm:gap-5 lg:px-7">
                        <span>Name</span>
                        <span>Violation Type</span>
                        <span class="text-center">Date</span>
                        <span class="sm:text-right">Status</span>
                    </div>
                    @foreach ([
                        ['Juan Dela Cruz', 'Cheating'],
                        ['Maria Clara', 'Uniform'],
                        ['Pedro Reyes', 'Bullying'],
                        ['Lagman Cruz', 'Lost ID'],
                        ['Izzy Gianan', 'Smoking/Vaping'],
                    ] as [$student, $violation])
                    <div class="violation-grid violation-copy grid grid-cols-1 gap-3 bg-gray-300 px-6 py-5 text-2xl text-gray-950 sm:items-center sm:gap-5 lg:px-7">
                        <span>{{ $student }}</span>
                        <span>{{ $violation }}</span>
                        <span class="text-center leading-tight">April 1, 2026 7:30<br>AM</span>
                        <span class="justify-self-start rounded-full bg-red-600 px-5 py-2 text-white sm:justify-self-end">Pending</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @elseif ($section === 'record')
            <div class="px-10 py-8">
                <h2 class="text-2xl font-black text-gray-900 tracking-tight">RECORD VIOLATION</h2>
            </div>
            @elseif ($section === 'history')
            <div class="px-10 py-8">
                <h2 class="text-2xl font-black text-gray-900 tracking-tight">TRACK VIOLATION HISTORY</h2>
            </div>
            @else
            <div class="px-10 py-8">
                <h2 class="text-2xl font-black text-gray-900 tracking-tight">APPLY CONSEQUENCES</h2>
            </div>
            @endif
        </main>
    </div>
</body>

</html>
