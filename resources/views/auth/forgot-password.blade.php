<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password | {{ config('app.name', 'Laravel') }}</title>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <!-- Ensure Alpine.js is loaded for interactivity -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body style="margin: 0; min-height: 100vh; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; font-family: 'Instrument Sans', sans-serif; padding: 16px; background-image: linear-gradient(rgba(235, 15, 15, 0.65), rgba(186, 114, 114, 0.65)), url('/images/bg-building.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat; background-attachment: fixed;">

    <!-- Form Card Container -->
    <div style="position: relative; width: 100%; max-width: 500px; background-color: #ffffff; border-radius: 16px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); padding: 80px 32px 32px 32px; margin: 80px auto 24px auto; box-sizing: border-box;">
        
        <!-- Logo Circle -->
        <div style="position: absolute; top: -70px; left: 50%; transform: translateX(-50%); width: 144px; height: 144px; background-color: #ffffff; border-radius: 50%; padding: 8px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); display: flex; align-items: center; justify-content: center;">
            <a href="{{ url('/') }}">
                <img src="/images/logo.jpg" alt="Logo" style="width: 100px; height: 100px; object-fit: contain;">
            </a>
        </div>

        <h2 style="text-align: center; font-size: 20px; font-weight: 700; margin-bottom: 15px;">Reset your password</h2>

        <div style="font-size: 14px; color: #4b5563; text-align: center; margin-bottom: 20px; line-height: 1.5;">
            {{ __('Forgot your password? No problem. Just enter your email address and we will email you a password reset link.') }}
        </div>

        <!-- Session Status (Success Message for Email Sent) -->
        @if (session('status'))
            <div style="margin-bottom: 16px; font-size: 14px; color: #16a34a; text-align: center; font-weight: 500;">
                {{ session('status') }}
            </div>
        @endif

        <!-- Forgot Password Form -->
        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Form Fields -->
            <div style="display: flex; flex-direction: column; gap: 12px;">
                
                <!-- Email Address -->
                <div>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Email Address" required autofocus 
                           style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; background-color: #f9fafb; box-sizing: border-box;">
                    
                    @error('email')
                        <span style="color: red; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        style="display: block; width: 100%; margin-top: 10px; background-color: #dc2626; color: #ffffff; font-weight: 700; padding: 12px; border: none; border-radius: 9999px; cursor: pointer; transition: background-color 0.2s;"
                        onmouseover="this.style.backgroundColor='#b91c1c'" 
                        onmouseout="this.style.backgroundColor='#dc2626'">
                    EMAIL PASSWORD RESET LINK
                </button>
            </div>
        </form>

        <!-- Back to Login Link -->
        <div style="margin-top: 20px; font-size: 14px; text-align: center; color: #4b5563;">
            Remembered your password? <a href="{{ route('login') }}" style="color: #22c55e; font-weight: bold; text-decoration: none;">Login here</a>
        </div>
    </div>
</body>
</html>