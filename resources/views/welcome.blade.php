<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>

        </style>
    @endif
</head>

<body
    class="min-h-screen w-full bg-cover bg-center bg-no-repeat relative flex flex-col items-center justify-center font-sans p-4"
    style="background-image: linear-gradient(rgba(235, 15, 15, 0.65), rgba(186, 114, 114, 0.65)), url('/images/bg-building.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">



    <div
        style="position: relative; width: 80%; max-width: 320px; height: 350px; background-color: #ffffff; border-radius: 16px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); padding: 80px 32px 48px 32px; margin: 24px auto; box-sizing: border-box; display: flex; flex-direction: column; align-items: center;">
        <div class="w-full space-y-6 mt-6 px-5">
            <div
                style="position: absolute; top: -70px; left: 50%; transform: translateX(-50%); width: 144px; height: 144px; background-color: #ffffff; border-radius: 50%; padding: 8px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); display: flex; align-items: center; justify-content: center;">

                <img src="/images/logo.jpg" alt="Logo" style="width: 100px; height: 100px; object-fit: contain;">

            </div>

            <a href="{{ route('admin.login') }}"
                style="display: block; width: 100%; text-align: center; background-color: #dc2626; color: #ffffff; font-weight: 700; padding: 12px 24px; border-radius: 9999px; text-decoration: none; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); box-sizing: border-box; transition: background-color 0.2s ease;"
                onmouseover="this.style.backgroundColor='#b91c1c'" onmouseout="this.style.backgroundColor='#dc2626'">
                Login as admin
            </a>

            <!-- Login as Student Button -->
            <a href="{{ route('login') }}"
                style="display: block; width: 100%; text-align: center; background-color: #dc2626; color: #ffffff; font-weight: 700; padding: 12px 24px; border-radius: 9999px; text-decoration: none; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); box-sizing: border-box; transition: background-color 0.2s ease;"
                onmouseover="this.style.backgroundColor='#b91c1c'" onmouseout="this.style.backgroundColor='#dc2626'">
                Login as student
            </a>

            <div style="margin-top: 40px; font-size: 14px; font-weight: 500; color: #4b5563; text-align: center;">
                No account? <a href="{{ route('register') }}"
                    style="color: #22c55e; text-decoration: none; transition: color 0.2s ease;"
                    onmouseover="this.style.color='#15803d'; this.style.textDecoration='underline';"
                    onmouseout="this.style.color='#22c55e'; this.style.textDecoration='none';">Register</a>
            </div>

        </div>
</body>

</html>