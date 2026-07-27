<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register | {{ config('app.name', 'Laravel') }}</title>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
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

        <h2 style="text-align: center; font-size: 20px; font-weight: 700; margin-bottom: 20px;">Create your account</h2>

        <form method="POST" action="{{ route('register') }}"
            x-data="{ 
                    form: { email: '', student_id: '', password: '', password_confirmation: '' }, 
                    errors: {} 
                }">
            @csrf

            <!-- Form Fields -->
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <input type="text" name="full_name" placeholder="Full Name" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; background-color: #f9fafb;">
                <x-input-error :messages="$errors->get('full_name')" class="mt-2" />

                <div>
                <input type="email" name="email" x-model="form.email" 
               @input.debounce.500ms="fetch('/validate-fields', {
                   method: 'POST',
                   headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                   body: JSON.stringify(form)
               }).then(r => r.json()).then(d => errors = d.errors || {})"
               placeholder="Email Address" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; background-color: #f9fafb;">
        <span style="color: red; font-size: 12px;" x-text="errors.email ? errors.email[0] : ''"></span>
                </div>

        <div>
                <input type="text" name="student_id" x-model="form.student_id" 
               @input.debounce.500ms="fetch('/validate-fields', {
                   method: 'POST',
                   headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                   body: JSON.stringify(form)
                    }).then(r => r.json()).then(d => errors = d.errors || {})"
                    placeholder="Student ID" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; background-color: #f9fafb;">
                <span style="color: red; font-size: 12px;" x-text="errors.student_id ? errors.student_id[0] : ''"></span>
        </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
            <!-- Year Level Dropdown -->
            <select name="year_level" required style="padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; background-color: #f9fafb; color: #6b7280;">
                <option value="" disabled selected>Year</option>
                <option value="1st Year">1st Year</option>
                <option value="2nd Year">2nd Year</option>
                <option value="3rd Year">3rd Year</option>
                <option value="4th Year">4th Year</option>
            </select>

            <!-- Course Input -->
            <input type="text" name="course" placeholder="Course" required style="padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; background-color: #f9fafb;">
        </div>
                
            <input type="text" name="section" placeholder="Section" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; background-color: #f9fafb;">
            <!-- Password -->
            <div class="mt-4" x-data="{ showPassword: false }">
                <div style="position: relative;">
                <input :type="showPassword ? 'text' : 'password'" 
                       name="password" 
                       x-model="form.password" 
                       @input.debounce.500ms="fetch('/validate-fields', {
                       method: 'POST',
                       headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                       body: JSON.stringify(form)
                       }).then(r => r.json()).then(d => errors = d.errors || {})"
                       placeholder="Password" 
                       required 
                       style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; background-color: #f9fafb;">
                
                       <span @click="showPassword = !showPassword" 
                        style="position: absolute; top: 50%; right: 12px; transform: translateY(-50%); cursor: pointer; color: #6b7280;">
                        <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 20px; height: 20px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-.274.837-.684 1.613-1.208 2.297M15.536 15.536A9.953 9.953 0 0112 17c-4.477 0-8.268-2.943-9.542-7a9.953 9.953 0 013.042-4.536M9.88 9.88a3 3 0 014.24 4.24" />
                        </svg>
                        <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 20px; height: 20px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.94 17.94A10.97 10.97 0 0112 19c-4.97 0-9.21-3.13-10.94-7.5A10.97 10.97 0 0112 5c4.97 0 9.21 3.13 10.94 7.5a10.97 10.97 0 01-4.06 5.44M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </span>
                </div>
                <span style="color: red; font-size: 12px;" x-text="errors.password ? errors.password[0] : ''"></span>
            </div>

            <!-- Confirm Password -->
            <div class="mt-4" x-data="{ showPasswordConfirmation: false }">
            <div style="position: relative;">
                <input :type="showPasswordConfirmation ? 'text' : 'password'" 
                name="password_confirmation" 
                x-model="form.password_confirmation" 
                placeholder="Confirm Password" 
                required 
                style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; background-color: #f9fafb;">
                
                <span @click="showPasswordConfirmation = !showPasswordConfirmation" 
                  style="position: absolute; top: 50%; right: 12px; transform: translateY(-50%); cursor: pointer; color: #6b7280;">
                <svg x-show="!showPasswordConfirmation" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 20px; height: 20px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-.274.837-.684 1.613-1.208 2.297M15.536 15.536A9.953 9.953 0 0112 17c-4.477 0-8.268-2.943-9.542-7a9.953 9.953 0 013.042-4.536M9.88 9.88a3 3 0 014.24 4.24" />
                </svg>
                <svg x-show="showPasswordConfirmation" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 20px; height: 20px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.94 17.94A10.97 10.97 0 0112 19c-4.97 0-9.21-3.13-10.94-7.5A10.97 10.97 0 0112 5c4.97 0 9.21 3.13 10.94 7.5a10.97 10.97 0 01-4.06 5.44M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </span>
                </div>
                <!-- Real-time local match check -->
                <span style="color: red; font-size: 12px; margin-top: 5px; display: block;" 
                    x-show="form.password_confirmation.length > 0 && form.password !== form.password_confirmation">
                    Passwords do not match
                </span>
                
                <!-- Keep your server-side error display for other validation rules -->
                <span style="color: red; font-size: 12px; margin-top: 5px; display: block;" 
                    x-text="errors.password_confirmation ? errors.password_confirmation[0] : ''">
                </span>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    style="display: block; width: 100%; margin-top: 20px; background-color: #dc2626; color: #ffffff; font-weight: 700; padding: 12px; border: none; border-radius: 9999px; cursor: pointer; transition: background-color 0.2s;"
                    onmouseover="this.style.backgroundColor='#b91c1c'" 
                    onmouseout="this.style.backgroundColor='#dc2626'">
                REGISTER <a href="{{ route('login') }}"></a>
            </button>
        </form>

        <div style="margin-top: 20px; font-size: 14px; text-align: center; color: #4b5563;">
            Already have an account? <a href="{{ route('login') }}" style="color: #22c55e; font-weight: bold; text-decoration: none;">Login here</a>
        </div>
    </div>
</body>
</html>