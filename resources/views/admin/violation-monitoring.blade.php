@extends('layouts.admin')

@section('content')
    <div class="flex min-h-0 flex-1 flex-col overflow-y-auto bg-gray-50">

        @if (session('success'))
            <div class="mx-auto mt-4 max-w-5xl rounded-lg bg-green-100 px-4 py-3 text-sm font-semibold text-green-800 shadow">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mx-auto mt-4 max-w-5xl rounded-lg bg-red-100 px-4 py-3 text-sm text-red-800 shadow">
                Please complete all required fields.
            </div>
        @endif

        {{-- Red Title Bar --}}
        <div class="bg-red-700 px-8 py-5 shadow">
            <h1 class="text-3xl font-black tracking-tight text-white">VIOLATOR</h1>
        </div>

        {{-- Form Area --}}
        <div class="flex-1 bg-white px-8 py-10">
            <form method="POST" action="{{ route('admin.violations.record.store') }}" class="mx-auto max-w-5xl"> 
                @csrf

                <div class="grid grid-cols-1 gap-x-16 gap-y-6 md:grid-cols-2">

                    {{-- Student ID --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">student ID number</label>
                        <select name="user_id" id="student-select" required
                            class="w-full rounded-md border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm focus:border-red-500 focus:ring-red-500">
                            <option value="">STUDENT ID</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}" data-student-id="{{ $student->student_id }}"
                                    @selected(old('user_id') == $student->id)>
                                    {{ $student->student_id }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Violation Type --}}
                    {{-- Offense --}}
<div>
    <label class="mb-1 block text-sm font-medium text-gray-700">Violation (Code of Discipline)</label>
    <select name="offense_code" id="offense-select" required
        class="w-full rounded-md border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm focus:border-red-500 focus:ring-red-500">
        <option value="">Select violation</option>
        <optgroup label="Minor Offenses">
            @foreach ($offenses as $code => $o)
                @if ($o[0] === 'minor')
                    <option value="{{ $code }}" @selected(old('offense_code') === $code)>{{ $code }} - {{ $o[1] }}</option>
                @endif
            @endforeach
        </optgroup>
        <optgroup label="Major Offenses">
            @foreach ($offenses as $code => $o)
                @if ($o[0] === 'major')
                    <option value="{{ $code }}" @selected(old('offense_code') === $code)>{{ $code }} - {{ $o[1] }}</option>
                @endif
            @endforeach
        </optgroup>
    </select>
    <p id="sanction-preview" class="mt-2 text-xs font-semibold text-red-700"></p>
</div>

                    {{-- Date and Time --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Date and Time</label>
                        <input type="datetime-local" name="occurred_at" required value="{{ old('occurred_at') }}"
                            class="w-full rounded-md border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm focus:border-red-500 focus:ring-red-500">
                    </div>

                    {{-- Location --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Location</label>
                        <select name="location" required
                            class="w-full rounded-md border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm focus:border-red-500 focus:ring-red-500">
                            <option value="">Select Location</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location }}" @selected(old('location') === $location)>{{ $location }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" required rows="4" placeholder="Enter description of the violation"
                            class="w-full rounded-md border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm focus:border-red-500 focus:ring-red-500">{{ old('description') }}</textarea>
                    </div>

                    {{-- Notification Section --}}
                    <div class="space-y-4">
                        <label class="block text-sm font-medium text-gray-700">ADMIN SEND NOTIFICATION</label>

                        <div
                            class="flex items-center justify-between rounded-md border border-gray-300 bg-gray-100 px-4 py-2.5">
                            <span class="text-sm text-gray-600">NOTIFICATION ALERT</span>
                            <input type="checkbox" name="notification_alert" value="1" @checked(old('notification_alert'))
                                class="h-5 w-5 rounded accent-red-700">
                        </div>

                        <div
                            class="flex min-h-[100px] items-center justify-center rounded-md border border-gray-300 bg-gray-100 px-4 text-center">
                            <label class="flex cursor-pointer items-center gap-3 text-sm font-medium">
                                <input type="checkbox" name="student_notification" value="1"
                                    @checked(old('student_notification')) class="h-4 w-4 accent-red-700">
                                <span>
                                    ADMIN ALERT<br>
                                    NOTIFICATION FOR<br>
                                    STUDENT
                                </span>
                            </label>
                        </div>
                    </div>

                    {{-- Reported by --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Reported by</label>
                        <input type="text" readonly value="{{ Auth::user()->name }}"
                            class="w-full rounded-md border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-600">
                    </div>

                </div>

                {{-- Buttons --}}
                <div class="mt-10 flex justify-end gap-3">
                    <button type="reset"
                        class="rounded-lg bg-red-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-red-700">
                        Cancel
                    </button>
                    <button type="submit"
                        class="rounded-lg bg-black px-7 py-2.5 text-sm font-medium text-white hover:bg-gray-800">
                        Next
                    </button>
                </div>
            </form>
            {{-- Add a new violation to the list --}}
<div class="mx-auto mt-12 max-w-5xl rounded-xl border border-gray-200 bg-gray-50 p-6">
    <h2 class="text-lg font-black text-gray-900">Add New Violation to the List</h2>
    <p class="text-sm text-gray-600 mb-4">Once added, it appears in the Violation dropdown above.</p>

    @if ($errors->offense->any())
        <div class="mb-4 rounded-lg bg-red-100 px-4 py-3 text-sm text-red-800">
            @foreach ($errors->offense->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('admin.offenses.store') }}" class="grid grid-cols-1 gap-4 md:grid-cols-3">
        @csrf
        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">Violation Code</label>
            <input type="text" name="code" value="{{ old('code') }}" maxlength="10" placeholder="e.g. MA27" required
                class="w-full rounded-md border border-gray-300 bg-white px-4 py-2.5 text-sm uppercase">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">Category</label>
            <select name="category" id="new-category" required
                class="w-full rounded-md border border-gray-300 bg-white px-4 py-2.5 text-sm">
                <option value="minor" @selected(old('category') === 'minor')>Minor</option>
                <option value="major" @selected(old('category') === 'major')>Major</option>
            </select>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">Violation Name</label>
            <input type="text" name="title" value="{{ old('title') }}" required
                class="w-full rounded-md border border-gray-300 bg-white px-4 py-2.5 text-sm">
        </div>

        <div id="new-path" class="md:col-span-3 hidden">
            <label class="mb-1 block text-sm font-medium text-gray-700">
                Sanction path (1st to 5th offense, leave unused steps empty)
            </label>
            <div class="grid grid-cols-5 gap-2">
                @for ($i = 0; $i < 5; $i++)
                    <select name="sanction_path[]" class="rounded-md border border-gray-300 bg-white px-2 py-2 text-sm">
                        <option value="">{{ $i + 1 }}{{ ['st','nd','rd','th','th'][$i] }} -</option>
                        @foreach (config('offenses.sanction_types') as $letter => $name)
                            <option value="{{ $letter }}" @selected(old("sanction_path.$i") === $letter)>{{ $letter }} - {{ $name }}</option>
                        @endforeach
                    </select>
                @endfor
            </div>
        </div>

        <div class="md:col-span-3 flex justify-end">
            <button type="submit" class="rounded-lg bg-red-700 px-6 py-2.5 text-sm font-medium text-white hover:bg-red-800">
                Add Violation
            </button>
        </div>
    </form>
</div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const newCategory = document.getElementById('new-category');
    const newPath = document.getElementById('new-path');
    function togglePath() { newPath.classList.toggle('hidden', newCategory.value !== 'major'); }
    newCategory?.addEventListener('change', togglePath);
    togglePath();
</script>
   <script>
    const counts = @json($counts);
    const offenses = @json($offenses);
    const minorPath = @json(config('offenses.minor_path'));
    const types = @json(config('offenses.sanction_types'));

    const studentSel = document.getElementById('student-select');
    const offenseSel = document.getElementById('offense-select');
    const preview = document.getElementById('sanction-preview');

    studentSel?.addEventListener('change', function () {
        const option = this.options[this.selectedIndex];
        this.options[0].textContent = option.dataset.studentId || 'STUDENT ID';
        updatePreview();
    });
    offenseSel?.addEventListener('change', updatePreview);

    function updatePreview() {
        const code = offenseSel.value, uid = studentSel.value;
        if (!code || !uid) { preview.textContent = ''; return; }
        const n = ((counts[uid] || {})[code] || 0) + 1;
        const [cat, , path] = offenses[code];
        let label;
        if (cat === 'minor') {
            label = minorPath[Math.min(n, minorPath.length) - 1];
        } else {
            const l = path[Math.min(n, path.length) - 1];
            label = l + ' - ' + types[l];
        }
        const max = cat === 'minor' ? minorPath.length : path.length;
        preview.textContent = 'Offense #' + n + ' (' + cat.toUpperCase() + ') - Sanction: ' + label + (n > max ? ' (beyond sanction path, review needed)' : '');
    }
</script>
@endpush