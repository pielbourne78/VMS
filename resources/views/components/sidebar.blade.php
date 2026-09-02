<aside id="sidebar" class="sidebar-closed grc-red text-white flex flex-col py-6 shadow-xl z-10 flex-shrink-0">
    <style>
        #sidebar {
            width: 320px;
            min-width: 320px;
            transition: all 0.3s ease;
            overflow: hidden;
            pointer-events: auto;
        }

        #sidebar.sidebar-closed {
            width: 0px !important;
            min-width: 0px !important;
            padding-left: 0px !important;
            padding-right: 0px !important;
            opacity: 0;
            pointer-events: none;
        }
    </style>

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