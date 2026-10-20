@php
    $sidebarUser = Auth::check() ? Auth::user() : null;
    $sidebarUserName = $sidebarUser?->name ?? 'Guest';
    $sidebarUserPhoto = $sidebarUser?->profile_photo_url ?? 'https://raw.githubusercontent.com/carlvilla/resources/main/student-avatar.png';
@endphp

<aside id="sidebar"
    class="relative grc-red text-white w-72 flex flex-col py-6 shadow-2xl z-10 flex-shrink-0 transition-all duration-300">
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

        #sidebar .sidebar-section-title {
            letter-spacing: 0.12em;
        }

        #sidebar.sidebar-closed {
            width: 0 !important;
            min-width: 0 !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            opacity: 0;
            pointer-events: none;
            overflow: hidden;
        }

        #sidebar.sidebar-emphasize {
            animation: sidebarPulse 0.5s ease;
        }

        #sidebar .sidebar-link {
            display: block;
            padding: 0.875rem 1.5rem;
            border-left: 4px solid transparent;
            transition: all 0.2s ease;
            letter-spacing: 0.08em;
            font-weight: 700;
            font-size: 0.82rem;
        }

        #sidebar .sidebar-link:hover,
        #sidebar .sidebar-link.active {
            background: rgba(88, 0, 13, 0.18);
            border-color: rgba(255, 255, 255, 0.9);
            box-shadow: inset 1px 0 0 rgba(255, 255, 255, 0.2);
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

    <button id="sidebarCloseArrow" onclick="toggleSidebar()"
        class="hidden absolute top-1/2 -translate-y-1/2 -right-6 w-12 h-12 bg-white text-grc-red rounded-full shadow-2xl border-4 border-grc-red flex items-center justify-center hover:bg-gray-100 hover:scale-110 transition-all z-20">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path>
        </svg>
    </button>

    <div class="px-6 flex flex-col items-center mb-10">
        <div class="relative mb-4">
            <img id="sidebar-avatar" src="{{ $sidebarUserPhoto }}" alt="Student Avatar"
                class="w-28 h-28 rounded-full border-4 border-white shadow-lg object-cover"
                onerror="this.onerror=null; this.src='https://raw.githubusercontent.com/carlvilla/resources/main/student-avatar.png';">
            <div class="absolute bottom-1 right-1 bg-green-500 w-6 h-6 rounded-full border-4 border-white"></div>
        </div>
        <h2 class="text-2xl font-extrabold uppercase tracking-wide text-center">{{ $sidebarUserName }}</h2>
        <p class="sidebar-section-title text-sm opacity-90 font-medium text-center">STUDENT</p>
    </div>

    <div class="px-6 space-y-5 text-base font-semibold">
        <div class="flex items-start gap-3">
            <span class="mt-1.5 text-lg">•</span>
            <p>{{ $sidebarUser?->course ?? 'BS Information Technology' }}</p>
        </div>
        <div class="flex items-start gap-3">
            <span class="mt-1.5 text-lg">•</span>
            <p>{{ $sidebarUser?->student_id ?? '2024-06-00128' }}</p>
        </div>
        <div class="flex items-start gap-3">
            <span class="mt-1.5 text-lg">•</span>
            <p>{{ $sidebarUser?->section ?? 'Section' }}</p>
        </div>
        <div class="flex items-start gap-3">
            <span class="mt-1.5 text-lg">•</span>
            <p>{{ $sidebarUser?->year_level ?? '3rd Year' }}</p>
        </div>
        <div class="flex items-start gap-3">
            <span class="mt-1.5 text-lg">•</span>
            <a href="{{ route('code.of.discipline') }}"
                class="border-b border-white pb-1 cursor-pointer hover:opacity-80 transition-opacity">
                ARTICLES VI CODE OF DISCIPLINE
            </a>
        </div>
    </div>

    <div class="mt-auto px-6 pt-6">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full bg-white text-grc-red font-black text-center py-2.5 rounded-full shadow-md hover:bg-gray-100 transition duration-150 text-sm tracking-widest">
                LOG OUT
            </button>
        </form>
    </div>
</aside>