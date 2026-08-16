<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles VI Code of Discipline - VMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Montserrat', sans-serif; }
        .grc-red { background-color: #BF1E2E; }
        .text-grc-red { color: #BF1E2E; }
        .border-grc-red { border-color: #BF1E2E; }
        .header-gradient { background: linear-gradient(90deg, #BF1E2E 0%, #A01A28 100%); }
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
                <a href="{{ route('dashboard') }}" class="hover:text-red-200 transition">DASHBOARD</a>
                <a href="#" class="hover:text-red-200 transition">VIOLATION MONITORING</a>
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
        <main class="flex-1 p-10 flex flex-col justify-between overflow-y-auto">
            <div class="discipline-container">
                
                <!-- ================= Step 1: Sec. 1 Sanction ================= -->
                <div id="step-1" class="discipline-step">
                    <h1 class="text-3xl font-extrabold text-center tracking-wide text-grc-red underline mb-8">
                        ARTICLES VI CODE OF DISCIPLINE
                    </h1>

                    <div class="space-y-4 text-sm md:text-base leading-relaxed text-justify text-gray-800">
                        <p>
                            <strong>SEC. 1 SANCTION</strong> A sanction may be imposed on a student for committing any of the offenses enumerated in the Code of Discipline (COD). All types of sanction except 1st to 3rd warning shall be appended with counseling and parent’s conference as part of intervention toward restoration. A sanction may take any of the following forms:
                        </p>
                        <p>
                            <strong>TYPES OF SANCTION A. Warning</strong> – A verbal and/or written reprimand with community service, counseling, and parent’s conference if necessary.
                        </p>
                        <p>
                            <strong>B. Community Service</strong> – Instead of fine, the erring student will have to render to the school community or whatever he/she will be assigned to perform representing sanction for the violation perpetrated.
                        </p>
                        <p>
                            <strong>C. Suspension</strong> – A penalty that allows the higher education institution to deprive or deny the erring student from attending classes for a period not exceeding (20%) twenty percent of the total class days for the school semester, but not less than seven (7) days. A penalty of suspension for a period of more than (20%) twenty percent of the total class days for the school semester shall be imposed upon justifiable decisions. During the involuntary and temporary leave of the student/s from the college, student/s shall not be allowed: 1) To attend class and/or any academic and non-academic activities such as any examinations and to participate in any activities within or outside the school premises (including foundation day and sports fest). 2) To enter the GRC building and its premises if the erring student is a threat to the peace and harmony of the academic community. 3) To avail of any other privilege of being a GRC student.
                        </p>
                        <p>
                            <strong>D. Non-readmission</strong> – a penalty that allows the institution to deny admission or enrollment of an erring student for one (1) school term immediately following the term when the resolution or decision is finding the student guilty of the offense charged and imposing the penalty of non-readmission was promulgated.
                        </p>
                        <p>
                            <strong>E. Exclusion</strong> – a penalty that allows the institutions to exclude or drop the name of the erring student from the roll of students. The subsequent transfer credentials shall be issued immediately.
                        </p>
                        <p>
                            <strong>F. Expulsion</strong> – a penalty wherein the institution declares an erring student disqualified for admission to any public or private higher education institution in the Philippines. This penalty may be imposed for acts or offenses involving moral turpitude or constituting gross misconduct, which are considered criminal under existing penal laws and upon the decision of the prefect committee and the Administrative Head of the institution.
                        </p>
                    </div>

                    <!-- Next Button on Step 1 -->
                    <div class="flex justify-end mt-10">
                        <button onclick="goToStep(2)" class="border-2 border-grc-red text-grc-red font-bold px-8 py-2.5 rounded-full shadow hover:bg-grc-red hover:text-white transition cursor-pointer">
                            NEXT
                        </button>
                    </div>
                </div>

                <!-- ================= Step 2: Sec. 2 & 3 ================= -->
                <div id="step-2" class="discipline-step" style="display: none;">
                    <h1 class="text-3xl font-extrabold text-center tracking-wide text-grc-red underline mb-8">
                        ARTICLES VI CODE OF DISCIPLINE (Continued)
                    </h1>

                    <div class="space-y-6 text-sm md:text-base leading-relaxed text-justify text-gray-800">
                        <div>
                            <p class="font-bold text-grc-red mb-2">SEC. 2 TYPES OF OFFENSES</p>
                            <p class="mb-3">
                                <strong>a) MINOR OFFENSE</strong> – an offense and/or violation due to misconduct or any violation that does not affect student’s learning or does not cause serious harm or damage to his/her fellow student or in the institution’s property. Penalties for this type of offense vary from verbal reprimand to not less than three (3) hours of community service.
                            </p>
                            <p>
                                <strong>b) MAJOR OFFENSE</strong> – a serious offense and/or violation committed against the provision of the Student Code of Discipline Handbook of GRC. Such violation shall be penalized or sanctioned by what the C.O.D has provided. Penalties for this type of offense vary from community service of not less than (9) nine hours to expulsion.
                            </p>
                        </div>

                        <div>
                            <p class="font-bold text-grc-red mb-2">SEC. 3 STANDARD CONDUCT OF GRC STUDENT</p>
                            <p class="mb-4">
                                The students of Global Reciprocal Colleges are expected to present themselves with politeness, respect, courtesy, trustworthiness, honesty, and humility to all members of the faculty, administrators, fellow students, and other institution’s personnel. The online class netiquette shall be strictly observed by the students.
                            </p>

                            <p class="font-bold text-grc-red mb-2">ONLINE CLASS ETIQUETTE</p>
                            <ol class="list-decimal list-inside space-y-2">
                                <li>The students shall use the official GRC account in signing up for official online platform.</li>
                                <li>Screen name and aliases shall not be accepted during the live class sessions.</li>
                                <li>The students shall use appropriate language and tone during online class sessions.</li>
                                <li>Respect and consideration for other students shall be observed at all times.</li>
                                <li>Sarcasm, humor, and/or posting of jokes are not allowed inside the platform used in online class.</li>
                                <li>Issues of privacy and information sharing outside of the class are strictly prohibited.</li>
                                <li>In all live classes, the students shall be logged-in at least ten (10) minutes before the designated class schedule. The students shall wait for the professor/teacher to be accepted in the platform used for the live class.</li>
                                <li>The students may wear a dress down attire during the live classes; however, a proper dress code is still be strictly observed.</li>
                                <li>The participants shall always turn ON and OFF the microphone during the live classes. The microphone shall only be turned on when permitted by their professor/teacher, and shall be turned off once done.</li>
                                <li>The students shall show an intention to speak by using available icons or simply by raising hands to ask permission to speak. In this manner, speaking simultaneously among the participants will be avoided.</li>
                                <li>The sharing of screen by the students shall be approved by the faculty member before being done. The screen containing personal information is advised to close before the screen sharing.</li>
                                <li>The students are encouraged to use the chat feature of the platform used for the live class for the conversations relevant to the topic. Exchanging of the off-topic conversations are strongly discouraged.</li>
                            </ol>
                        </div>
                    </div>

                    <!-- Back and Next Buttons on Step 2 -->
                    <div class="flex justify-between mt-10">
                        <button onclick="goToStep(1)" class="border-2 border-grc-red text-grc-red font-bold px-8 py-2.5 rounded-full shadow hover:bg-grc-red hover:text-white transition cursor-pointer">
                            BACK
                        </button>
                        <button onclick="goToStep(3)" class="border-2 border-grc-red text-grc-red font-bold px-8 py-2.5 rounded-full shadow hover:bg-grc-red hover:text-white transition cursor-pointer">
                            NEXT
                        </button>
                    </div>
                </div>

                <!-- ================= Step 3: Sec. 4 Minor Offenses ================= -->
                <div id="step-3" class="discipline-step" style="display: none;">
                    <h1 class="text-3xl font-extrabold text-center tracking-wide text-grc-red underline mb-8">
                        SEC. 4 MINOR OFFENSES AND ITS SANCTIONS
                    </h1>

                    <div class="overflow-x-auto mb-8">
                        <table class="w-full border-collapse border border-gray-400 text-sm md:text-base text-gray-800">
                            <thead>
                                <tr class="bg-gray-200 text-center font-bold">
                                    <th class="border border-gray-400 px-4 py-2.5 w-1/5">CODE</th>
                                    <th class="border border-gray-400 px-4 py-2.5 w-4/5">DESCRIPTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border border-gray-400 px-4 py-3 text-center font-bold align-top">MI1</td>
                                    <td class="border border-gray-400 px-4 py-3 align-top">Not wearing and/or refusing to present school Identification Card (I.D) upon entering the campus.</td>
                                </tr>
                                <tr class="bg-gray-50">
                                    <td class="border border-gray-400 px-4 py-3 text-center font-bold align-top">MI2</td>
                                    <td class="border border-gray-400 px-4 py-3 align-top">Entering the class without school Identification Card (I.D) and/or not in proper uniform, unless otherwise, the student/s has permit provided by OSA.</td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-400 px-4 py-3 text-center font-bold align-top">MI3</td>
                                    <td class="border border-gray-400 px-4 py-3 align-top">
                                        <p class="font-semibold mb-2">Cross Dressing</p>
                                        <p class="mb-2">Cross dressing such as but not limited to:</p>
                                        <p class="mb-2"><strong>a)</strong> The male student/s of Global Reciprocal Colleges shall not be allowed wearing ripped/tattered jeans, earring and/or any body piercing, cap, make up, hairbands/hairclips, loud hair color, bra and other female accessories inside the campus and its premises.</p>
                                        <p><strong>b)</strong> The female student/s of Global Reciprocal Colleges wearing ripped/tattered jeans, mini shorts, mini skirt, sleeveless blouses and any other revealing clothes, two (2) or more earrings and/or any body piercing and loud hair color will be barred from entering the campus.</p>
                                    </td>
                                </tr>
                                <tr class="bg-gray-50">
                                    <td class="border border-gray-400 px-4 py-3 text-center font-bold align-top">MI4</td>
                                    <td class="border border-gray-400 px-4 py-3 align-top">Use of school facilities and/or school equipment without permissions.</td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-400 px-4 py-3 text-center font-bold align-top">MI5</td>
                                    <td class="border border-gray-400 px-4 py-3 align-top">Derogatory such as but not limited to creating unnecessary noise, use of foul languages, and/or any equivalent to demeaning gestures, within the school premises, or on social media, or in any online learning platform that might cause damage to the image of the institution or any of its personnel.</td>
                                </tr>
                                <tr class="bg-gray-50">
                                    <td class="border border-gray-400 px-4 py-3 text-center font-bold align-top">MI6</td>
                                    <td class="border border-gray-400 px-4 py-3 align-top">Public Display of Affection (PDA)</td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-400 px-4 py-3 text-center font-bold align-top">MI7</td>
                                    <td class="border border-gray-400 px-4 py-3 align-top">Eating or Drinking inside Laboratories</td>
                                </tr>
                                <tr class="bg-gray-50">
                                    <td class="border border-gray-400 px-4 py-3 text-center font-bold align-top">MI8</td>
                                    <td class="border border-gray-400 px-4 py-3 align-top">Littering.</td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-400 px-4 py-3 text-center font-bold align-top">MI9</td>
                                    <td class="border border-gray-400 px-4 py-3 align-top">Entering the restricted area inside the institution without any permission from the school authority.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="space-y-2 text-sm md:text-base text-gray-800 bg-gray-50 p-6 rounded-lg border border-gray-300 mb-10">
                        <p class="font-bold text-grc-red mb-3">SANCTIONS:</p>
                        <p><strong>i.</strong> 1<sup>st</sup> offense – Recorded Verbal Reprimand.</p>
                        <p><strong>ii.</strong> 2<sup>nd</sup> offense – Written Reprimand with Community Service of three (3) hours.</p>
                        <p><strong>iii.</strong> 3<sup>rd</sup> offense – Written Reprimand with Community Service of six (6) hours.</p>
                        <p><strong>iv.</strong> 4<sup>th</sup> offense – Written Reprimand with Community Service of nine (9) hours.</p>
                        <p><strong>v.</strong> 5<sup>th</sup> offense – Written Reprimand with Community Service of twelve (12) hours.</p>
                    </div>

                    <div class="flex justify-between mt-10">
                        <button onclick="goToStep(2)" class="border-2 border-grc-red text-grc-red font-bold px-8 py-2.5 rounded-full shadow hover:bg-grc-red hover:text-white transition cursor-pointer">
                            BACK
                        </button>
                        <button onclick="goToStep(4)" class="border-2 border-grc-red text-grc-red font-bold px-8 py-2.5 rounded-full shadow hover:bg-grc-red hover:text-white transition cursor-pointer">
                            NEXT
                        </button>
                    </div>
                </div>

                <!-- ================= Step 4: Sec. 5 Major Offenses (Part 1 - MA1 to MA18) ================= -->
                <div id="step-4" class="discipline-step" style="display: none;">
                    <h1 class="text-3xl font-extrabold text-center tracking-wide text-grc-red underline mb-8">
                        SEC. 5 MAJOR OFFENSES AND ITS SANCTIONS
                    </h1>

                    <div class="overflow-x-auto mb-8">
                        <table class="w-full border-collapse border border-gray-400 text-sm md:text-base text-gray-800">
                            <thead>
                                <tr class="bg-gray-200 text-center font-bold">
                                    <th class="border border-gray-400 px-3 py-2 w-1/12">CODE</th>
                                    <th class="border border-gray-400 px-4 py-2 w-8/12">DESCRIPTION</th>
                                    <th class="border border-gray-400 px-2 py-2 w-3/12" colspan="5">SANCTIONS</th>
                                </tr>
                                <tr class="bg-gray-100 text-center font-bold text-xs">
                                    <th class="border border-gray-400 px-3 py-1" colspan="2"></th>
                                    <th class="border border-gray-400 px-1 py-1">1<sup>st</sup></th>
                                    <th class="border border-gray-400 px-1 py-1">2<sup>nd</sup></th>
                                    <th class="border border-gray-400 px-1 py-1">3<sup>rd</sup></th>
                                    <th class="border border-gray-400 px-1 py-1">4<sup>th</sup></th>
                                    <th class="border border-gray-400 px-1 py-1">5<sup>th</sup></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border border-gray-400 px-3 py-3 text-center font-bold align-middle">MA1</td>
                                    <td class="border border-gray-400 px-4 py-3 align-middle">Sexual Harassment (R.A 7877) and/or acts of Lasciviousness (under Article 336 of the revised penal code).</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">D</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">E</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center align-middle"></td>
                                    <td class="border border-gray-400 px-2 py-3 text-center align-middle"></td>
                                    <td class="border border-gray-400 px-2 py-3 text-center align-middle"></td>
                                </tr>
                                <tr class="bg-gray-50">
                                    <td class="border border-gray-400 px-3 py-3 text-center font-bold align-middle">MA2</td>
                                    <td class="border border-gray-400 px-4 py-3 align-middle">Sexual Immortality and obscenity (Pre-marital sex and action leading to it).</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">D</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">E</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center align-middle"></td>
                                    <td class="border border-gray-400 px-2 py-3 text-center align-middle"></td>
                                    <td class="border border-gray-400 px-2 py-3 text-center align-middle"></td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-400 px-3 py-3 text-center font-bold align-middle">MA3</td>
                                    <td class="border border-gray-400 px-4 py-3 align-middle">Participating Fraternity, gang and sorority and gang including exercising hazing.</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">D</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">E</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center align-middle"></td>
                                    <td class="border border-gray-400 px-2 py-3 text-center align-middle"></td>
                                    <td class="border border-gray-400 px-2 py-3 text-center align-middle"></td>
                                </tr>
                                <tr class="bg-gray-50">
                                    <td class="border border-gray-400 px-3 py-3 text-center font-bold align-middle">MA4</td>
                                    <td class="border border-gray-400 px-4 py-3 align-middle">Possession of Liquor and/ or under the influence of Liquor</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">C</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">C</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">D</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">E</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center align-middle"></td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-400 px-3 py-3 text-center font-bold align-middle">MA5</td>
                                    <td class="border border-gray-400 px-4 py-3 align-middle">Possession of illegal drugs and/ or under the influence of illegal drugs.</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">E</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center align-middle"></td>
                                    <td class="border border-gray-400 px-2 py-3 text-center align-middle"></td>
                                    <td class="border border-gray-400 px-2 py-3 text-center align-middle"></td>
                                    <td class="border border-gray-400 px-2 py-3 text-center align-middle"></td>
                                </tr>
                                <tr class="bg-gray-50">
                                    <td class="border border-gray-400 px-3 py-3 text-center font-bold align-middle">MA6</td>
                                    <td class="border border-gray-400 px-4 py-3 align-middle">Possession of cigarette and / or smoking whether or not inside the campus while wearing the school I.D and uniform.</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">B</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">C</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">D</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">E</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center align-middle"></td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-400 px-3 py-3 text-center font-bold align-middle">MA7</td>
                                    <td class="border border-gray-400 px-4 py-3 align-middle">Possession of deadly weapon such as knife, ice-picked, guns and/ or any harmful objects.</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">C</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">D</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">E</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center align-middle"></td>
                                    <td class="border border-gray-400 px-2 py-3 text-center align-middle"></td>
                                </tr>
                                <tr class="bg-gray-50">
                                    <td class="border border-gray-400 px-3 py-3 text-center font-bold align-middle">MA8</td>
                                    <td class="border border-gray-400 px-4 py-3 align-middle">Theft.</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">B</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">C</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">D</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">E</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center align-middle"></td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-400 px-3 py-3 text-center font-bold align-middle">MA9</td>
                                    <td class="border border-gray-400 px-4 py-3 align-middle">Extortion.</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">C</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">D</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">E</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center align-middle"></td>
                                    <td class="border border-gray-400 px-2 py-3 text-center align-middle"></td>
                                </tr>
                                <tr class="bg-gray-50">
                                    <td class="border border-gray-400 px-3 py-3 text-center font-bold align-middle">MA10</td>
                                    <td class="border border-gray-400 px-4 py-3 align-middle">Bribery.</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">C</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">C</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">D</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">E</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center align-middle"></td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-400 px-3 py-3 text-center font-bold align-middle">MA11</td>
                                    <td class="border border-gray-400 px-4 py-3 align-middle">Acts of violence, assault or physical injuries against fellow students, faculty members, administrators, and/ or to other personnel.</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">B</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">C</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">D</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">E</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center align-middle"></td>
                                </tr>
                                <tr class="bg-gray-50">
                                    <td class="border border-gray-400 px-3 py-3 text-center font-bold align-middle">MA12</td>
                                    <td class="border border-gray-400 px-4 py-3 align-middle">Slandering and/ or labeling against fellow students, faculty member, administrators, and any institution’s personnel.</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">B</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">C</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">D</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">E</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center align-middle"></td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-400 px-3 py-3 text-center font-bold align-middle">MA13</td>
                                    <td class="border border-gray-400 px-4 py-3 align-middle">Forging, falsification and/ or tampering of any documents.</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">C</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">D</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">E</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center align-middle"></td>
                                    <td class="border border-gray-400 px-2 py-3 text-center align-middle"></td>
                                </tr>
                                <tr class="bg-gray-50">
                                    <td class="border border-gray-400 px-3 py-3 text-center font-bold align-middle">MA14</td>
                                    <td class="border border-gray-400 px-4 py-3 align-middle">Forming and participating in any union and/ or any acts of rebellion against administration in any form including the use of social media.</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">D</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">E</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center align-middle"></td>
                                    <td class="border border-gray-400 px-2 py-3 text-center align-middle"></td>
                                    <td class="border border-gray-400 px-2 py-3 text-center align-middle"></td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-400 px-3 py-3 text-center font-bold align-middle">MA15</td>
                                    <td class="border border-gray-400 px-4 py-3 align-middle">Willful disobedience and/ or insubordination.</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">B</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">B</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">C</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">C</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">D</td>
                                </tr>
                                <tr class="bg-gray-50">
                                    <td class="border border-gray-400 px-3 py-3 text-center font-bold align-middle">MA16</td>
                                    <td class="border border-gray-400 px-4 py-3 align-middle">Any forms of corruption.</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">C</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">D</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">E</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center align-middle"></td>
                                    <td class="border border-gray-400 px-2 py-3 text-center align-middle"></td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-400 px-3 py-3 text-center font-bold align-middle">MA17</td>
                                    <td class="border border-gray-400 px-4 py-3 align-middle">Any forms of dishonesty such as but not limited to borrowing and/ or lending I.D or COM to his/her fellow students, cheating, plagiarism and etc.</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">B</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">B</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">C</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">D</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">E</td>
                                </tr>
                                <tr class="bg-gray-50">
                                    <td class="border border-gray-400 px-3 py-3 text-center font-bold align-middle">MA18</td>
                                    <td class="border border-gray-400 px-4 py-3 align-middle">Destruction of school’s property including vandalism.</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">B</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">C</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">D</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">E</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center align-middle"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-between mt-10">
                        <button onclick="goToStep(3)" class="border-2 border-grc-red text-grc-red font-bold px-8 py-2.5 rounded-full shadow hover:bg-grc-red hover:text-white transition cursor-pointer">
                            BACK
                        </button>
                        <button onclick="goToStep(5)" class="border-2 border-grc-red text-grc-red font-bold px-8 py-2.5 rounded-full shadow hover:bg-grc-red hover:text-white transition cursor-pointer">
                            NEXT
                        </button>
                    </div>
                </div>

                <!-- ================= Step 5: Sec. 5 Major Offenses (Part 2 - MA19 to MA26) ================= -->
                <div id="step-5" class="discipline-step" style="display: none;">
                    <h1 class="text-3xl font-extrabold text-center tracking-wide text-grc-red underline mb-8">
                        SEC. 5 MAJOR OFFENSES AND ITS SANCTIONS (Continued)
                    </h1>

                    <div class="overflow-x-auto mb-8">
                        <table class="w-full border-collapse border border-gray-400 text-sm md:text-base text-gray-800">
                            <thead>
                                <tr class="bg-gray-200 text-center font-bold">
                                    <th class="border border-gray-400 px-3 py-2 w-1/12">CODE</th>
                                    <th class="border border-gray-400 px-4 py-2 w-8/12">DESCRIPTION</th>
                                    <th class="border border-gray-400 px-2 py-2 w-3/12" colspan="5">SANCTIONS</th>
                                </tr>
                                <tr class="bg-gray-100 text-center font-bold text-xs">
                                    <th class="border border-gray-400 px-3 py-1" colspan="2"></th>
                                    <th class="border border-gray-400 px-1 py-1">1<sup>st</sup></th>
                                    <th class="border border-gray-400 px-1 py-1">2<sup>nd</sup></th>
                                    <th class="border border-gray-400 px-1 py-1">3<sup>rd</sup></th>
                                    <th class="border border-gray-400 px-1 py-1">4<sup>th</sup></th>
                                    <th class="border border-gray-400 px-1 py-1">5<sup>th</sup></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border border-gray-400 px-3 py-3 text-center font-bold align-middle">MA19</td>
                                    <td class="border border-gray-400 px-4 py-3 align-middle">Possession, use and / or distribution of pornographic materials including pornographic site in the internet.</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">B</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">C</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">D</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">E</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center align-middle"></td>
                                </tr>
                                <tr class="bg-gray-50">
                                    <td class="border border-gray-400 px-3 py-3 text-center font-bold align-middle">MA20</td>
                                    <td class="border border-gray-400 px-4 py-3 align-middle">Any form of gambling.</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">B</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">C</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">D</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">E</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center align-middle"></td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-400 px-3 py-3 text-center font-bold align-middle">MA21</td>
                                    <td class="border border-gray-400 px-4 py-3 align-middle">Unauthorized solicitation and activities without permission from the Office of the Student Affairs.</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">B</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">C</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">D</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">E</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center align-middle"></td>
                                </tr>
                                <tr class="bg-gray-50">
                                    <td class="border border-gray-400 px-3 py-3 text-center font-bold align-middle">MA22</td>
                                    <td class="border border-gray-400 px-4 py-3 align-middle">Cyber Crime.</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">B</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">C</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">D</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">E</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center align-middle"></td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-400 px-3 py-3 text-center font-bold align-middle">MA23</td>
                                    <td class="border border-gray-400 px-4 py-3 align-middle">Invasion of Privacy such as Appropriate of Name, Likeness and? Or some other personal attribute, Intrusion Upon Seclusion, False, Light, and Public Disclosure of Private Facts.</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">B</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">C</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">D</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">E</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center align-middle"></td>
                                </tr>
                                <tr class="bg-gray-50">
                                    <td class="border border-gray-400 px-3 py-3 text-center font-bold align-middle">MA24</td>
                                    <td class="border border-gray-400 px-4 py-3 align-middle">Violating the restrictions on fire safety policy.</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">B</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">C</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">D</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">E</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center align-middle"></td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-400 px-3 py-3 text-center font-bold align-middle">MA25</td>
                                    <td class="border border-gray-400 px-4 py-3 align-middle">Causing and/ or creating commotion.</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">B</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">B</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">C</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">D</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">E</td>
                                </tr>
                                <tr class="bg-gray-50">
                                    <td class="border border-gray-400 px-3 py-3 text-center font-bold align-middle">MA26</td>
                                    <td class="border border-gray-400 px-4 py-3 align-middle">Bullying such as but not limited to physical bullying, relational bullying and/ or cyber bullying.</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">B</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">B</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">C</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">D</td>
                                    <td class="border border-gray-400 px-2 py-3 text-center font-bold align-middle">E</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-between mt-10">
                        <button onclick="goToStep(4)" class="border-2 border-grc-red text-grc-red font-bold px-8 py-2.5 rounded-full shadow hover:bg-grc-red hover:text-white transition cursor-pointer">
                            BACK
                        </button>
                        <button onclick="goToStep(6)" class="border-2 border-grc-red text-grc-red font-bold px-8 py-2.5 rounded-full shadow hover:bg-grc-red hover:text-white transition cursor-pointer">
                            NEXT
                        </button>
                    </div>
                </div>

                <!-- ================= Step 6: Student's Case Procedure ================= -->
                <div id="step-6" class="discipline-step" style="display: none;">
                    <h1 class="text-3xl font-extrabold text-center tracking-wide text-grc-red underline mb-8">
                        STUDENT’S CASE PROCEDURE
                    </h1>

                    <div class="space-y-4 text-sm md:text-base leading-relaxed text-justify text-gray-800">
                        <p>
                            All cases shall be heard with strict observance of due process. Complaints shall be filed at the Office of the Student Affairs (OSA). The Office of the Student Affairs shall have the jurisdiction only in the cases where the student/s is the respondents.
                        </p>

                        <ol class="list-decimal list-inside space-y-4 pt-2">
                            <li>
                                <strong>COMPLAINT RECEIVED:</strong> The respondent/s shall submit an incident report to OSA.
                            </li>
                            <li>
                                <strong>PRELIMINARY INVESTIGATION &amp; EVALUATION:</strong> The Office of the Student Affairs shall conduct a preliminary investigation and evaluation to determine the prima-facie of the case by accomplishing violation form. There shall be a thorough investigation; gathered facts and evidence shall be presented. The case filed shall be categorized whether it is a MINOR OFFENSE or a MAJOR OFFENSE. <em>(During the hearing of the case, a student/s facing major administrative charges may be preventively suspended from attending his classes or from entering the school premises upon written order of a duly authorized officer of the school. If the student is a threat to the peace and harmony of the academic community, the parent or legal guardian of the erring student shall be his/her representative during the investigation procedure.)</em>
                            </li>
                            <li>
                                <strong>DECISION &amp; RESOLUTION:</strong> After the preliminary investigation and evaluation of the case, decision and resolution shall be formulated by the Prefect Committee.
                            </li>
                            <li>
                                <strong>APPEAL (Respondent/s may appeal once only):</strong> After the prefect committee released the decision and resolution, the respondent/s shall have ten (10) days to appeal the case before it proceeds to implementation. If and only if the resolution is a suspension to expulsion. If the respondent/s did not appeal the case within ten (10) days after the decision and resolution were released, the case shall then be automatically closed and will proceed to the implementation and intervention. But, if the respondent/s appeals a motion for reconsideration in the appealing committee, the appealing committee shall raise the appeal of the respondent/s to the Administrative Head’s Office. The appealing committee together with the Administrative Head shall then review the case and decide if the motion for reconsideration be denied or granted.
                            </li>
                            <li>
                                <strong>APPEAL DENIED:</strong> If the motion for reconsideration has been denied, the case will automatically proceed to implementation headed by the Prefect Head. After the implementation of the decision, the intervention will follow which will be conducted by the Guidance Counselor.
                            </li>
                            <li>
                                <strong>APPEAL GRANTED:</strong> But, if the appeal to motion for reconsideration has been granted, the appealing committee will formulate a decision and resolution that will be implemented by the Prefect Head then forwarded to Guidance Office for the intervention.
                            </li>
                            <li>
                                <strong>CASE CLOSED.</strong>
                            </li>
                        </ol>
                    </div>

                    <!-- Back Button on Step 6 -->
                    <div class="flex justify-start mt-10">
                        <button onclick="goToStep(5)" class="border-2 border-grc-red text-grc-red font-bold px-8 py-2.5 rounded-full shadow hover:bg-grc-red hover:text-white transition cursor-pointer">
                            BACK
                        </button>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- JavaScript for Sidebar Toggle & Multi-Step Navigation -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('hidden');
        }

        function goToStep(stepNumber) {
            // Hide all steps
            for (let i = 1; i <= 6; i++) {
                const el = document.getElementById('step-' + i);
                if (el) el.style.display = 'none';
            }

            // Show the target step
            const target = document.getElementById('step-' + stepNumber);
            if (target) target.style.display = 'block';

            // Smooth scroll back to top of content
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    </script>
</body>
</html>