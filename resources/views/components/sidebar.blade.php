<aside id="sidebar" class="sidebar-closed relative grc-red text-white flex flex-col py-6 shadow-xl z-10 flex-shrink-0">
    <style>
        #sidebar {
            width: 320px;
            min-width: 320px;
            transition: all 0.3s ease;
            overflow: hidden;
            pointer-events: auto;
            background: linear-gradient(180deg, #f55565 0%, #b02836 100%);
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15);
        }

        #sidebar.sidebar-closed {
            width: 0px !important;
            min-width: 0px !important;
            padding-left: 0px !important;
            padding-right: 0px !important;
            opacity: 0;
            pointer-events: none;
        }

        #sidebar.sidebar-emphasize {
            animation: sidebarPulse 0.5s ease;
        }

        @keyframes sidebarPulse {
            0% {
                box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15), 0 0 0 0 rgba(191, 30, 46, 0.6);
            }
            70% {
                box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15), 0 0 0 20px rgba(191, 30, 46, 0);
            }
            100% {
                box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15), 0 0 0 0 rgba(191, 30, 46, 0);
            }
        }
    </style>

    <!-- Close Arrow Tab -->
    <button id="sidebarCloseArrow" onclick="toggleSidebar()"
        class="hidden absolute top-1/2 -translate-y-1/2 -right-6 w-12 h-12 bg-white text-grc-red rounded-full shadow-2xl border-4 border-grc-red flex items-center justify-center hover:bg-gray-100 hover:scale-110 transition-all z-20">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path>
        </svg>
    </button>

    <!-- College Logo & Name -->
    <div class="px-6 flex items-center gap-3 border-b border-red-700 pb-6 mb-6">
        <img src="{{ asset('images/logo.jpg') }}" alt="GRC Logo" class="h-12 w-12 rounded-full object-cover bg-white p-1 shadow-md">
        <div>
            <h1 class="text-lg font-bold leading-tight">Global<br>Reciprocal<br>Colleges</h1>
        </div>
    </div>

    <!-- Student Profile Section -->
    <div class="px-6 flex flex-col items-center mb-10">
        <div class="relative mb-4">
            <img id="sidebar-avatar" src="{{ Auth::user()->profile_photo_url }}" alt="Student Avatar"
                class="w-28 h-28 rounded-full border-4 border-white shadow-lg object-cover"
                onerror="this.onerror=null; this.src='https://raw.githubusercontent.com/carlvilla/resources/main/student-avatar.png';">
            <div class="absolute bottom-1 right-1 bg-green-500 w-6 h-6 rounded-full border-4 border-white"></div>
        </div>
        <h2 class="text-2xl font-extrabold uppercase tracking-wide">{{ Auth::user()->name }}</h2>
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