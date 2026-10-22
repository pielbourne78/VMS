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
            background: linear-gradient(180deg, #b61727 0%, #6E1019 100%);
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

    {{-- Hide arrow (inside the edge so overflow:hidden doesn't clip it) --}}
<button id="sidebarCloseArrow" onclick="toggleSidebar()" title="Hide sidebar"
    class="absolute top-1/2 -translate-y-1/2 right-2 w-9 h-9 bg-white text-grc-red rounded-full shadow-xl border-2 border-grc-red flex items-center justify-center hover:bg-gray-100 hover:scale-110 transition-all z-20">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path>
    </svg>
</button>

<div class="px-6 flex flex-col items-center mb-8">
    <div class="relative mb-4">
        <img id="sidebar-avatar" src="{{ $sidebarUserPhoto }}" alt="Student Avatar"
            class="w-28 h-28 rounded-full border-4 border-white shadow-lg object-cover"
            onerror="this.onerror=null; this.src='https://raw.githubusercontent.com/carlvilla/resources/main/student-avatar.png';">
        <div class="absolute bottom-1 right-1 bg-green-500 w-6 h-6 rounded-full border-4 border-white"></div>
    </div>
    <p class="text-[10px] uppercase tracking-widest opacity-70 font-bold">Name</p>
    <h2 class="text-2xl font-extrabold uppercase tracking-wide text-center leading-tight">{{ $sidebarUserName }}</h2>
    <span class="sidebar-section-title mt-2 bg-white/20 border border-white/40 rounded-full px-3 py-0.5 text-[11px] font-bold tracking-widest">STUDENT</span>
</div>

@php
    $sidebarItems = [
        ['Course', $sidebarUser?->course ?? 'BS Information Technology',
            'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z'],
        ['Student ID', $sidebarUser?->student_id ?? '2024-06-00128',
            'M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2'],
        ['Section', $sidebarUser?->section ?? 'Section',
            'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
        ['Year Level', $sidebarUser?->year_level ?? '3rd Year',
            'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
    ];
@endphp

<div class="px-6 space-y-4 text-base font-semibold">
    @foreach ($sidebarItems as [$label, $value, $icon])
        <div class="flex items-center gap-3">
            <span class="flex-shrink-0 w-9 h-9 rounded-full bg-white/20 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"></path>
                </svg>
            </span>
            <div class="min-w-0">
                <p class="text-[10px] uppercase tracking-widest opacity-70 font-bold">{{ $label }}</p>
                <p class="leading-tight">{{ $value }}</p>
            </div>
        </div>
    @endforeach

    <a href="{{ route('code.of.discipline') }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
        <span class="flex-shrink-0 w-9 h-9 rounded-full bg-white/20 flex items-center justify-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
        </span>
        <span class="border-b border-white pb-0.5 leading-tight">ARTICLES VI CODE OF DISCIPLINE</span>
    </a>
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