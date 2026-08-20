<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Global Reciprocal Colleges - Violation Monitoring</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Montserrat', sans-serif; }
        .grc-red { background-color: #BF1E2E; }
        .text-grc-red { color: #BF1E2E; }
        .border-grc-red { border-color: #BF1E2E; }
        .header-gradient { background: linear-gradient(90deg, #BF1E2E 0%, #A01A28 100%); }
        .stat-btn-gradient { background: linear-gradient(90deg, #BF1E2E 0%, #E32B3E 100%); }
    </style>
</head>
<body class="bg-gray-100 antialiased flex min-h-screen overflow-x-hidden">

    <!-- Reusable Sidebar Component -->
    @include('components.sidebar')

    <!-- ================= Main Content ================= -->
    <div class="flex-1 flex flex-col bg-white">
        <!-- Header Navigation -->
        <header class="header-gradient text-white px-8 py-6 shadow-lg flex items-center justify-between border-b-4 border-grc-red flex-shrink-0">
            <nav class="flex items-center gap-8 text-lg font-semibold tracking-tight">
                <!-- Hamburger Toggle Button -->
                <svg onclick="toggleSidebar()" class="w-8 h-8 cursor-pointer hover:text-red-200 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                <a href="{{ url('/dashboard') }}" class="hover:text-red-200 transition">DASHBOARD</a>
                <a href="{{ route('violation.monitoring') }}" class="stat-btn-gradient text-white px-6 py-2 rounded-full shadow-inner">VIOLATION MONITORING</a>
                <a href="#" class="hover:text-red-200 transition">REPORT</a>
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

        <!-- Content Body -->
        <main class="flex-1 grc-red p-8 flex flex-col overflow-y-auto">
            
            <!-- Tabs Navigation -->
            <div class="flex justify-center gap-12 text-white font-bold text-base mb-8">
                <div class="cursor-pointer pb-1 transition-all border-b-2 border-transparent" id="tab-0" onclick="switchTab(0)">ACTIVE CASE</div>
                <div class="cursor-pointer pb-1 transition-all border-b-2 border-transparent" id="tab-1" onclick="switchTab(1)">RESOLVED CASE</div>
                <div class="cursor-pointer pb-1 transition-all border-b-2 border-transparent" id="tab-2" onclick="switchTab(2)">TOTAL VIOLATION</div>
                <div class="cursor-pointer pb-1 transition-all border-b-2 border-transparent" id="tab-3" onclick="switchTab(3)">PENDING</div>
            </div>

            <!-- White Card Container -->
            <div class="bg-white rounded-3xl p-8 max-w-4xl w-full mx-auto shadow-2xl min-h-[350px]">
                
                <!-- TAB 0: ACTIVE CASE -->
                <div class="tab-content" id="content-0">
                    <div class="grid grid-cols-3 font-bold text-grc-red text-base border-b-2 border-gray-200 pb-4 mb-6">
                        <div>CODE | VIOLATION:</div>
                        <div>SANCTIONS</div>
                        <div>DUE DATE:</div>
                    </div>
                    <div class="text-center text-gray-400 italic py-16 text-sm">
                        No active case records found. Data will appear here once added by the admin.
                    </div>
                </div>

                <!-- TAB 1: RESOLVED CASE -->
                <div class="tab-content hidden" id="content-1">
                    <div class="grid grid-cols-3 font-bold text-grc-red text-base border-b-2 border-gray-200 pb-4 mb-6">
                        <div>CODE | VIOLATION:</div>
                        <div>PENALTY LIST:</div>
                        <div>DONE:</div>
                    </div>
                    <div class="text-center text-gray-400 italic py-16 text-sm">
                        No resolved case records found. Data will appear here once updated by the admin.
                    </div>
                </div>

                <!-- TAB 2: TOTAL VIOLATION -->
                <div class="tab-content hidden" id="content-2">
                    <div class="grid grid-cols-3 font-bold text-grc-red text-base border-b-2 border-gray-200 pb-4 mb-6">
                        <div>CODE | VIOLATION:</div>
                        <div>STATUS</div>
                        <div>DATE</div>
                    </div>
                    <div class="text-center text-gray-400 italic py-16 text-sm">
                        No total violation records found.
                    </div>
                </div>

                <!-- TAB 3: PENDING -->
                <div class="tab-content hidden" id="content-3">
                    <div class="grid grid-cols-3 font-bold text-grc-red text-base border-b-2 border-gray-200 pb-4 mb-6">
                        <div>CODE | VIOLATION:</div>
                        <div>DETAILS</div>
                        <div>STATUS</div>
                    </div>
                    <div class="text-center text-gray-400 italic py-16 text-sm">
                        No pending records found.
                    </div>
                </div>

            </div>

        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('w-0');
            sidebar.classList.toggle('w-80');
            sidebar.classList.toggle('px-0');
        }

        function switchTab(index) {
            for (let i = 0; i < 4; i++) {
                const tabEl = document.getElementById('tab-' + i);
                const contentEl = document.getElementById('content-' + i);
                
                if (i === index) {
                    tabEl.classList.add('border-white');
                    tabEl.classList.remove('border-transparent', 'opacity-75');
                    contentEl.classList.remove('hidden');
                } else {
                    tabEl.classList.remove('border-white');
                    tabEl.classList.add('border-transparent', 'opacity-75');
                    contentEl.classList.add('hidden');
                }
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('tab-0').classList.add('border-white');
        });
    
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('sidebar-closed');
    }
    </script>

</body>
</html>