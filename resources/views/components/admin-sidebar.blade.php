<aside id="adminSidebar" class="admin-sidebar-collapsed grc-red text-white w-72 flex flex-col py-6 shadow-2xl z-10 flex-shrink-0 relative transition-all duration-300">

    <!-- Admin Avatar & Info -->
    <div class="px-6 flex flex-col items-center mb-8">
        <div class="w-24 h-24 rounded-full bg-gray-300 overflow-hidden shadow-lg border-4 border-white mb-3 flex items-center justify-center">
            <img src="{{ Auth::user()->profile_photo_url }}" alt="Admin Avatar"
                class="w-full h-full object-cover"
                onerror="this.onerror=null; this.src='https://raw.githubusercontent.com/carlvilla/resources/main/student-avatar.png';">
        </div>
        <h3 class="text-lg font-black uppercase tracking-wider text-center">{{ Auth::user()->name ?? 'ADMIN' }}</h3>
        <p class="text-xs font-bold tracking-widest opacity-90 uppercase">ADMIN</p>
    </div>

    <!-- Sidebar Navigation Menu -->
    <nav class="flex flex-col space-y-1 font-bold text-sm">
    <a href="{{ route('admin.violation.monitoring') }}"
        class="px-6 py-3.5 border-l-4 tracking-wide transition-all duration-200 hover:border-white hover:bg-red-900/30 {{ request()->routeIs('admin.violation.monitoring') ? 'bg-red-900/50 border-white' : 'border-transparent' }}">
        Record Violation
    </a>

    <a href="{{ route('admin.violations.index') }}"
        class="px-6 py-3.5 border-l-4 tracking-wide transition-all duration-200 hover:border-white hover:bg-red-900/30 {{ request()->routeIs('admin.violations.index') ? 'bg-red-900/50 border-white' : 'border-transparent' }}">
        Track violation History
    </a>

    <a href="{{ route('admin.consequences') }}"
        class="px-6 py-3.5 border-l-4 tracking-wide transition-all duration-200 hover:border-white hover:bg-red-900/30 {{ request()->routeIs('admin.consequences') ? 'bg-red-900/50 border-white' : 'border-transparent' }}">
        Apply Consequences
    </a>
</nav>

    <!-- Collapse Arrow (only visible when sidebar is open) -->
    <button id="adminSidebarCloseArrow" onclick="toggleAdminSidebar()"
        class="hidden absolute right-[-14px] top-64 bg-gray-200 text-gray-700 w-7 h-7 rounded-full flex items-center justify-center shadow-md cursor-pointer border border-gray-300 font-bold hover:bg-white transition z-20">
        &lt;
    </button>

    <!-- Logout Button -->
    <div class="mt-auto px-6">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full bg-white text-grc-red font-black text-center py-2.5 rounded-full shadow-md hover:bg-gray-100 transition duration-150 text-sm tracking-widest">
                LOG OUT
            </button>
        </form>
    </div>
</aside>