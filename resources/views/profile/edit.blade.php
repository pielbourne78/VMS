@extends('layouts.student')

@section('title', 'Edit Profile')

@section('content')
<main class="flex-1 p-8 overflow-y-auto bg-gray-50/50">
    <div class="max-w-3xl mx-auto space-y-6">

        <!-- Profile Picture Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-1">Profile Picture</h3>
            <p class="text-sm text-gray-600 mb-6">Upload or change your profile picture.</p>

            <div class="flex items-center gap-5 mb-4">
                <img id="preview-avatar" src="{{ data_get($user, 'profile_photo_url') }}" alt="Profile"
                    class="w-20 h-20 rounded-full object-cover border-2 border-gray-200"
                    onerror="this.onerror=null; this.src='https://raw.githubusercontent.com/carlvilla/resources/main/student-avatar.png';">

                @if(data_get($user, 'profile_picture'))
                    <form method="post" action="{{ route('profile.picture.destroy') }}"
                        onsubmit="return confirm('Are you sure you want to remove your profile picture?')"
                        class="mb-1">
                        @csrf
                        @method('delete')
                        <button type="submit"
                            class="text-xs text-red-600 hover:text-red-800 font-semibold cursor-pointer">Remove
                            Picture</button>
                    </form>
                @endif
            </div>

            <!-- Profile Picture Form -->
            <form method="post" action="{{ route('profile.picture.update') }}" class="space-y-5"
                enctype="multipart/form-data">
                @csrf

                <div>
                    <label for="profile_picture"
                        class="block text-sm font-semibold text-gray-700 mb-1">Choose Image</label>
                    <input id="profile_picture" name="profile_picture" type="file" accept="image/*"
                        onchange="previewImage(event)"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:rounded-full file:border-0 file:bg-grc-red file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:opacity-90">
                    @error('profile_picture')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-4 pt-2">
                    <button type="button" onclick="confirmPictureUpload()"
                        class="stat-btn-gradient text-white font-bold px-8 py-3 rounded-full shadow-md hover:opacity-90 transition duration-150 text-sm tracking-wider cursor-pointer">
                        UPLOAD PICTURE
                    </button>
                    @if (session('status') === 'profile-picture-updated')
                        <p id="picture-saved-msg" class="text-sm text-green-600 font-medium">Profile picture
                            updated successfully!</p>
                        <script>setTimeout(() => { const el = document.getElementById('picture-saved-msg'); if (el) el.style.display = 'none'; }, 3000);</script>
                    @endif
                </div>

                <!-- Picture Confirmation Modal -->
                <div id="picture-modal"
                    class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
                    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Confirm Picture Upload</h3>
                        <p class="text-sm text-gray-600 mb-6">Are you sure you want to apply this new
                            profile picture?</p>
                        <div class="flex justify-end gap-3">
                            <button type="button" onclick="closeModal('picture-modal')"
                                class="px-6 py-2.5 rounded-full border border-gray-300 text-gray-700 font-bold text-sm hover:bg-gray-50 transition">CANCEL</button>
                            <button type="submit"
                                class="stat-btn-gradient text-white font-bold px-6 py-2.5 rounded-full shadow-md hover:opacity-90 transition text-sm cursor-pointer">YES,
                                UPLOAD</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Profile Information Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-1">Profile Information</h3>
            <p class="text-sm text-gray-600 mb-6">Update your account profile information.</p>

            <form id="profile-info-form" method="post" action="{{ route('profile.update') }}"
                class="space-y-5">
                @csrf
                @method('patch')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="name"
                            class="block text-sm font-semibold text-gray-700 mb-1">Name</label>
                        <input id="name" name="name" type="text"
                            value="{{ old('name', data_get($user, 'name')) }}" required autofocus
                            autocomplete="name"
                            class="w-full rounded-xl border-gray-300 shadow-sm focus:border-grc-red focus:ring focus:ring-grc-red/20 px-4 py-2.5 text-sm">
                        @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="full_name" class="block text-sm font-semibold text-gray-700 mb-1">Full
                            Name</label>
                        <input id="full_name" name="full_name" type="text"
                            value="{{ old('full_name', data_get($user, 'full_name')) }}"
                            class="w-full rounded-xl border-gray-300 shadow-sm focus:border-grc-red focus:ring focus:ring-grc-red/20 px-4 py-2.5 text-sm">
                        @error('full_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    @if(data_get($user, 'is_admin'))
                        <div>
                            <label for="student_id"
                                class="block text-sm font-semibold text-gray-700 mb-1">Employee ID</label>
                            <input id="student_id" name="student_id" type="text"
                                value="{{ old('student_id', data_get($user, 'student_id')) }}"
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-grc-red focus:ring focus:ring-grc-red/20 px-4 py-2.5 text-sm">
                            @error('student_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="course"
                                class="block text-sm font-semibold text-gray-700 mb-1">Position</label>
                            <input id="course" name="course" type="text"
                                value="{{ old('course', data_get($user, 'course')) }}"
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-grc-red focus:ring focus:ring-grc-red/20 px-4 py-2.5 text-sm">
                            @error('course')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="section"
                                class="block text-sm font-semibold text-gray-700 mb-1">Department</label>
                            <input id="section" name="section" type="text"
                                value="{{ old('section', data_get($user, 'section')) }}"
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-grc-red focus:ring focus:ring-grc-red/20 px-4 py-2.5 text-sm">
                            @error('section')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    @else
                        <div>
                            <label for="student_id"
                                class="block text-sm font-semibold text-gray-700 mb-1">Student ID</label>
                            <input id="student_id" name="student_id" type="text"
                                value="{{ old('student_id', data_get($user, 'student_id')) }}"
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-grc-red focus:ring focus:ring-grc-red/20 px-4 py-2.5 text-sm">
                            @error('student_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="course"
                                class="block text-sm font-semibold text-gray-700 mb-1">Course</label>
                            <input id="course" name="course" type="text"
                                value="{{ old('course', data_get($user, 'course')) }}"
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-grc-red focus:ring focus:ring-grc-red/20 px-4 py-2.5 text-sm">
                            @error('course')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="section"
                                class="block text-sm font-semibold text-gray-700 mb-1">Section</label>
                            <input id="section" name="section" type="text"
                                value="{{ old('section', data_get($user, 'section')) }}"
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-grc-red focus:ring focus:ring-grc-red/20 px-4 py-2.5 text-sm">
                            @error('section')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="year_level" class="block text-sm font-semibold text-gray-700 mb-1">Year
                                Level</label>
                            <select id="year_level" name="year_level"
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-grc-red focus:ring focus:ring-grc-red/20 px-4 py-2.5 text-sm">
                                <option value="">Select Year Level</option>
                                <option value="1st Year" {{ old('year_level', data_get($user, 'year_level')) == '1st Year' ? 'selected' : '' }}>1st Year</option>
                                <option value="2nd Year" {{ old('year_level', data_get($user, 'year_level')) == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                <option value="3rd Year" {{ old('year_level', data_get($user, 'year_level')) == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                                <option value="4th Year" {{ old('year_level', data_get($user, 'year_level')) == '4th Year' ? 'selected' : '' }}>4th Year</option>
                            </select>
                            @error('year_level')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    @endif
                </div>

                <div class="flex items-center gap-4 pt-2">
                    <button type="button" onclick="openModal('profile-modal')"
                        class="stat-btn-gradient text-white font-bold px-8 py-3 rounded-full shadow-md hover:opacity-90 transition duration-150 text-sm tracking-wider cursor-pointer">
                        SAVE CHANGES
                    </button>
                    @if (session('status') === 'profile-updated')
                        <p id="saved-msg" class="text-sm text-green-600 font-medium">Saved.</p>
                        <script>setTimeout(() => { const el = document.getElementById('saved-msg'); if (el) el.style.display = 'none'; }, 2000);</script>
                    @endif
                </div>

                <!-- Profile Info Confirmation Modal -->
                <div id="profile-modal"
                    class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
                    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Confirm Profile Update</h3>
                        <p class="text-sm text-gray-600 mb-6">Are you sure you want to save these changes to
                            your account?</p>
                        <div class="flex justify-end gap-3">
                            <button type="button" onclick="closeModal('profile-modal')"
                                class="px-6 py-2.5 rounded-full border border-gray-300 text-gray-700 font-bold text-sm hover:bg-gray-50 transition">CANCEL</button>
                            <button type="submit"
                                class="stat-btn-gradient text-white font-bold px-6 py-2.5 rounded-full shadow-md hover:opacity-90 transition text-sm">CONFIRM</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Update Email Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-1">Update Email</h3>
            <p class="text-sm text-gray-600 mb-6">Update your account email address.</p>

            <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
                @csrf
                @method('patch')
                <input type="hidden" name="name" value="{{ data_get($user, 'name') }}">
                <input type="hidden" name="full_name" value="{{ data_get($user, 'full_name') }}">
                <input type="hidden" name="student_id" value="{{ data_get($user, 'student_id') }}">
                <input type="hidden" name="course" value="{{ data_get($user, 'course') }}">
                <input type="hidden" name="section" value="{{ data_get($user, 'section') }}">
                @if(!data_get($user, 'is_admin'))
                    <input type="hidden" name="year_level" value="{{ data_get($user, 'year_level') }}">
                @endif

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                    <input id="email" name="email" type="email"
                        value="{{ old('email', data_get($user, 'email')) }}" autocomplete="username"
                        class="w-full rounded-xl border-gray-300 shadow-sm focus:border-grc-red focus:ring focus:ring-grc-red/20 px-4 py-2.5 text-sm">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-4 pt-2">
                    <button type="submit"
                        class="stat-btn-gradient text-white font-bold px-8 py-3 rounded-full shadow-md hover:opacity-90 transition duration-150 text-sm tracking-wider">
                        UPDATE EMAIL
                    </button>
                    @if (session('status') === 'profile-updated')
                        <p id="email-saved-msg" class="text-sm text-green-600 font-medium">Saved.</p>
                        <script>setTimeout(() => { const el = document.getElementById('email-saved-msg'); if (el) el.style.display = 'none'; }, 2000);</script>
                    @endif
                </div>
            </form>
        </div>

        <!-- Delete Account Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 p-6">
            <h3 class="text-lg font-bold text-red-600 mb-1">Delete Account</h3>
            <p class="text-sm text-gray-600 mb-6">Once your account is deleted, all of its resources and
                data will be permanently deleted.</p>

            <button type="button"
                onclick="document.getElementById('delete-modal').classList.remove('hidden')"
                class="bg-red-600 text-white font-bold px-8 py-3 rounded-full shadow-md hover:bg-red-700 transition duration-150 text-sm tracking-wider">
                DELETE ACCOUNT
            </button>

            <div id="delete-modal"
                class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
                <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Are you sure you want to delete your
                        account?</h3>
                    <p class="text-sm text-gray-600 mb-6">Once your account is deleted, all of its resources
                        and data will be permanently deleted. Please enter your password to confirm.</p>

                    <form method="post" action="{{ route('profile.destroy') }}" class="space-y-4">
                        @csrf
                        @method('delete')

                        <div>
                            <label for="password"
                                class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                            <input id="password" name="password" type="password"
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-grc-red focus:ring focus:ring-grc-red/20 px-4 py-2.5 text-sm">
                            @error('userDeletion.password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end gap-3">
                            <button type="button"
                                onclick="document.getElementById('delete-modal').classList.add('hidden')"
                                class="px-6 py-2.5 rounded-full border border-gray-300 text-gray-700 font-bold text-sm hover:bg-gray-50 transition">
                                CANCEL
                            </button>
                            <button type="submit"
                                class="bg-red-600 text-white font-bold px-6 py-2.5 rounded-full shadow-md hover:bg-red-700 transition text-sm">
                                DELETE ACCOUNT
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    function confirmPictureUpload() {
        const fileInput = document.getElementById('profile_picture');
        if (!fileInput.files || fileInput.files.length === 0) {
            alert('Please select an image file first.');
            return;
        }
        openModal('picture-modal');
    }

    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
        }
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function () {
            const preview = document.getElementById('preview-avatar');
            const sidebarAvatar = document.getElementById('sidebar-avatar');

            if (preview) preview.src = reader.result;
            if (sidebarAvatar) sidebarAvatar.src = reader.result;
        }
        if (event.target.files && event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>
@endpush