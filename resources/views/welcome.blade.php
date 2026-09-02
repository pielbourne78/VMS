<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        :root {
            --bg-overlay: rgba(95, 12, 12, 0.72);
            --card-bg: rgba(255, 255, 255, 0.96);
            --primary: #dc2626;
            --primary-dark: #b91c1c;
            --primary-soft: #fee2e2;
            --text: #111827;
            --muted: #4b5563;
            --success: #16a34a;
            --success-dark: #15803d;
            --shadow: rgba(17, 24, 39, 0.18);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: linear-gradient(rgba(235, 15, 15, 0.65), rgba(186, 114, 114, 0.65)), url('/images/bg-building.jpg') center/cover no-repeat fixed;
            font-family: 'Instrument Sans', sans-serif;
            color: var(--text);
        }

        .auth-shell {
            position: relative;
            width: min(100%, 380px);
            background: var(--card-bg);
            border-radius: 24px;
            box-shadow: 0 24px 60px -18px var(--shadow);
            padding: 84px 28px 28px;
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .brand-mark {
            position: absolute;
            top: -64px;
            left: 50%;
            transform: translateX(-50%);
            width: 136px;
            height: 136px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.98);
            border-radius: 50%;
            box-shadow: 0 16px 30px -12px rgba(17, 24, 39, 0.22);
            border: 4px solid rgba(220, 38, 38, 0.08);
        }

        .brand-mark img {
            width: 96px;
            height: 96px;
            object-fit: contain;
            display: block;
        }

        .auth-content {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .welcome-copy {
            text-align: center;
            margin-bottom: 8px;
        }

        .welcome-copy h1 {
            margin: 0;
            font-size: clamp(1.6rem, 3vw, 2.1rem);
            font-weight: 700;
            letter-spacing: -0.04em;
            color: var(--text);
        }

        .welcome-copy p {
            margin: 8px 0 0;
            font-size: 0.95rem;
            color: var(--muted);
            line-height: 1.5;
        }

        .auth-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 14px 20px;
            border-radius: 9999px;
            background: linear-gradient(135deg, var(--primary) 0%, #ef4444 100%);
            color: #fff;
            font-size: 1rem;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 12px 18px -12px rgba(220, 38, 38, 0.8);
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .auth-link:hover,
        .auth-link:focus {
            transform: translateY(-1px);
            box-shadow: 0 16px 20px -12px rgba(220, 38, 38, 0.75);
            background: linear-gradient(135deg, var(--primary-dark) 0%, #dc2626 100%);
        }

        .register-text {
            margin-top: 8px;
            text-align: center;
            font-size: 0.95rem;
            font-weight: 500;
            color: var(--muted);
        }

        .register-text a {
            color: var(--success);
            font-weight: 700;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .register-text a:hover,
        .register-text a:focus {
            color: var(--success-dark);
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            body {
                padding: 18px;
            }

            .auth-shell {
                padding: 76px 20px 22px;
                border-radius: 20px;
            }

            .brand-mark {
                width: 120px;
                height: 120px;
                top: -58px;
            }

            .brand-mark img {
                width: 84px;
                height: 84px;
            }
        }
    </style>
</head>

<body>
    <main class="auth-shell" aria-label="Authentication section">
        <div class="brand-mark" aria-label="Organization logo">
            <img src="/images/logo.jpg" alt="Logo">
        </div>

        <div class="auth-content">
            <div class="welcome-copy">
                <h1>Welcome</h1>
                <p>Access the violation management system</p>
            </div>

            <a href="{{ route('admin.login') }}" class="auth-link">
                Login as admin
            </a>

            <a href="{{ route('login') }}" class="auth-link">
                Login as student
            </a>

            <div class="register-text">
                No account?
                <a href="{{ route('register') }}">Register</a>
            </div>
        </div>
    </main>
</body>

</html>