<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password | {{ config('app.name', 'Laravel') }}</title>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <!-- Ensure Alpine.js is loaded for show/hide and live validation functionality -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="min-h-screen w-full bg-cover bg-center bg-no-repeat relative flex flex-col items-center justify-center font-sans p-4" 
style="background-image: linear-gradient(rgba(235, 15, 15, 0.65), rgba(186, 114, 114, 0.65)), url('/images/bg-building.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">

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
            {{ __('Forgot your password? No problem. Just enter your email address and choose a new password below.') }}
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div style="margin-bottom: 16px; font-size: 14px; color: #16a34a; text-align: center; font-weight: 500;">
                {{ session('status') }}
            </div>
        @endif

        <!-- Wrapped in a single parent x-data scope so state is shared properly -->
        <form method="POST" action="{{ route('password.store') }}" 
              x-data="{ 
                  form: { password: '', password_confirmation: '' }, 
                  errors: {},
                  showPassword: false,
                  showConfirmPassword: false,
                  get hasMinLength() { return this.form.password.length >= 8; },
                  get hasMixedCase() { return /[a-z]/.test(this.form.password) && /[A-Z]/.test(this.form.password); },
                  get hasNumber() { return /\d/.test(this.form.password); },
                  get hasSymbol() { return /[^A-Za-z0-9]/.test(this.form.password); }
              }">
            @csrf

            <!-- CRITICAL: Hidden Token and Email fields required by NewPasswordController -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            <input type="hidden" name="email" value="{{ $request->email }}">

            <!-- Form Fields -->
            <div style="display: flex; flex-direction: column; gap: 12px;">
                
                <!-- Notice text replacing the editable email input since email is passed securely -->
                <div style="font-size: 13px; color: #4b5563; background-color: #f3f4f6; padding: 10px; border-radius: 8px; text-align: center;">
                    Resetting password for: <strong>{{ $request->email }}</strong>
                </div>

                <!-- New Password Field with Show/Hide Toggle & Live Rules -->
                <div style="margin-bottom: 4px;">
                    <div style="position: relative;">
                        <input :type="showPassword ? 'text' : 'password'" 
                               name="password" 
                               x-model="form.password" 
                               placeholder="New Password" 
                               required 
                               style="width: 100%; padding: 10px 40px 10px 10px; border: 1px solid #d1d5db; border-radius: 8px; background-color: #f9fafb; box-sizing: border-box;">
                        
                        <!-- Show/Hide Icon Button for Password -->
                        <button type="button" @click="showPassword = !showPassword" 
                                style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #6b7280; font-size: 12px;">
                            <span x-text="showPassword ? 'Hide' : 'Show'"></span>
                        </button>
                    </div>

                    <!-- Real-time Interactive Password Rules Checklist -->
                    <div style="margin-top: 6px; font-size: 12px; display: flex; flex-direction: column; gap: 3px;" x-show="form.password.length > 0">
                        <span :style="hasMinLength ? 'color: #16a34a;' : 'color: #6b7280;'">
                            <span x-text="hasMinLength ? '✔' : '•'"></span> At least 8 characters
                        </span>
                        <span :style="hasMixedCase ? 'color: #16a34a;' : 'color: #6b7280;'">
                            <span x-text="hasMixedCase ? '✔' : '•'"></span> Upper and lowercase letters
                        </span>
                        <span :style="hasNumber ? 'color: #16a34a;' : 'color: #6b7280;'">
                            <span x-text="hasNumber ? '✔' : '•'"></span> At least one number
                        </span>
                        <span :style="hasSymbol ? 'color: #16a34a;' : 'color: #6b7280;'">
                            <span x-text="hasSymbol ? '✔' : '•'"></span> At least one special character
                        </span>
                    </div>

                    @error('password')
                        <span style="color: red; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password Field with Show/Hide Toggle & Match Check -->
                <div style="margin-bottom: 4px;">
                    <div style="position: relative;">
                        <input :type="showConfirmPassword ? 'text' : 'password'" 
                               name="password_confirmation" 
                               x-model="form.password_confirmation" 
                               placeholder="Confirm Password" 
                               required 
                               style="width: 100%; padding: 10px 40px 10px 10px; border: 1px solid #d1d5db; border-radius: 8px; background-color: #f9fafb; box-sizing: border-box;">
                        
                        <button type="button" @click="showConfirmPassword = !showConfirmPassword" 
                                style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #6b7280; font-size: 12px;">
                            <span x-text="showConfirmPassword ? 'Hide' : 'Show'"></span>
                        </button>
                    </div>
                    
                    <span style="color: red; font-size: 12px; margin-top: 5px; display: block;" 
                          x-show="form.password_confirmation.length > 0 && form.password !== form.password_confirmation">
                        The password confirmation does not match.
                    </span>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        style="display: block; width: 100%; margin-top: 10px; background-color: #dc2626; color: #ffffff; font-weight: 700; padding: 12px; border: none; border-radius: 9999px; cursor: pointer; transition: background-color 0.2s;"
                        onmouseover="this.style.backgroundColor='#b91c1c'" 
                        onmouseout="this.style.backgroundColor='#dc2626'">
                    RESET PASSWORD
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