<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login | {{ config('app.name', 'Laravel') }}</title>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
</head>

<body class="relative min-h-screen w-full font-sans p-4"
    style="background: url('/images/bg-building.jpg') center/cover no-repeat fixed;">

    <div
        style="position: fixed; top: 0; left: 0; width: 100%; height: 100vh; background: linear-gradient(rgba(235, 15, 15, 0.65), rgba(186, 114, 114, 0.65)); z-index: -1;">
    </div>

    <div
        style="position: relative; width: 100%; max-width: 420px; background-color: #ffffff; border-radius: 16px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); padding: 80px 32px 32px 32px; margin: 80px auto 24px auto; box-sizing: border-box;">
        <div
            style="position: absolute; top: -70px; left: 50%; transform: translateX(-50%); width: 144px; height: 144px; background-color: #ffffff; border-radius: 50%; padding: 8px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); display: flex; align-items: center; justify-content: center;">
            <a href="{{ url('/') }}">
                <img src="/images/logo.jpg" alt="Logo" style="width: 100px; height: 100px; object-fit: contain;">
            </a>
        </div>

        <h2 style="text-align: center; font-size: 20px; font-weight: 700; margin-bottom: 10px;">Administrator Portal
        </h2>
        <p style="text-align: center; font-size: 13px; color: #6b7280; margin-bottom: 20px;">Sign in with your
            administrator credentials</p>

        @if (session('status'))
            <div
                style="background-color: #d1fae5; color: #065f46; padding: 10px; border-radius: 8px; margin-bottom: 15px; text-align: center; font-size: 14px;">
                {{ session('status') }}
            </div>
        @endif

        @if (session('error'))
            <div
                style="background-color: #fee2e2; color: #991b1b; padding: 10px; border-radius: 8px; margin-bottom: 15px; text-align: center; font-size: 14px;">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <input type="hidden" name="login_as" value="admin">

            <div style="margin-bottom: 15px;">
                <input type="email" name="email" placeholder="Admin Email" value="{{ old('email') }}" required autofocus
                    required
                    style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; background-color: #f9fafb; box-sizing: border-box;">

                <x-input-error :messages="$errors->get('email')" class="mt-2" />

            </div>

            <div style="margin-bottom: 15px; position: relative;">
                <input type="password" name="password" id="password" placeholder="Password" required
                    style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; background-color: #f9fafb; box-sizing: border-box;">
            </div>

            <div style="display: flex; align-items: center; margin-bottom: 20px;">
                <input type="checkbox" id="remember_me" name="remember" style="margin-right: 8px;">
                <label for="remember_me" style="font-size: 14px; color: #4b5563;">Remember me</label>
            </div>

            <button type="submit"
                style="display: block; width: 100%; background-color: #dc2626; color: #ffffff; font-weight: 700; padding: 12px; border: none; border-radius: 9999px; cursor: pointer; transition: background-color 0.2s;"
                onmouseover="this.style.backgroundColor='#b91c1c'" onmouseout="this.style.backgroundColor='#dc2626'">
                SIGN IN
            </button>
        </form>

        <div style="margin-top: 20px; font-size: 14px; text-align: center; color: #4b5563;">
            Back to <a href="{{ route('welcome') }}"
                style="color: #3b82f6; font-weight: bold; text-decoration: none;">Homepage</a>
        </div>
    </div>
</body>

</html>