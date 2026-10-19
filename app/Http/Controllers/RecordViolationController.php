<?php

namespace App\Http\Controllers;
use App\Models\Offense;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Violation;
use App\Notifications\ViolationStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecordViolationController extends Controller
{
   public function create()
{
    $students = User::where('role', 'student')->orderBy('student_id')->get();

    $offenses = Offense::where('is_active', true)->orderBy('id')->get()
    ->mapWithKeys(fn ($o) => [$o->code => [$o->category, $o->title, $o->sanction_path]])
    ->all();

    // counts[user_id][offense_code] = how many times already
    $counts = Violation::where('status', '!=', 'dismissed')
        ->whereNotNull('offense_code')
        ->selectRaw('user_id, offense_code, COUNT(*) as total')
        ->groupBy('user_id', 'offense_code')
        ->get()
        ->groupBy('user_id')
        ->map(fn ($rows) => $rows->pluck('total', 'offense_code'));

    $locations = ['Building A','Building B','Canteen','Library','Parking Area','Gymnasium','Classroom'];

    return view('admin.violation-monitoring', compact('students', 'offenses', 'counts', 'locations'));
}

public function store(Request $request)
{
    $validated = $request->validate([
        'user_id' => 'required|exists:users,id',
        'offense_code' => ['required', Rule::exists('offenses', 'code')->where('is_active', true)],
        'occurred_at' => 'required|date',
        'location' => 'required|string',
        'description' => 'required|string',
    ]);

    $offense = Offense::where('code', $validated['offense_code'])->firstOrFail();
    $category = $offense->category;
    $title = $offense->title;

    $number = Violation::where('user_id', $validated['user_id'])
        ->where('offense_code', $validated['offense_code'])
        ->where('status', '!=', 'dismissed')
        ->count() + 1;

    $sanction = Violation::sanctionFor($validated['offense_code'], $number);

   $violation = Violation::create([
    'violation_code' => Violation::caseIdFor($validated['offense_code'], $number, $validated['user_id']),
    'user_id' => $validated['user_id'],
    'issued_by' => Auth::id(),
    'violation_type' => $title,
    'offense_code' => $validated['offense_code'],
    'offense_category' => $category,
    'offense_number' => $number,
    'sanction_code' => $sanction['letter'],
    'sanction_label' => $sanction['label'],
    'beyond_path' => $sanction['beyond'],
    'description' => $validated['location'] . ' — ' . $validated['description'],
    'occurred_at' => $validated['occurred_at'],
    'status' => 'pending',
]);

    $student = User::find($violation->user_id);
    if ($student instanceof User) {
        $student->notify(new ViolationStatusUpdated([
            'title' => 'Violation Recorded',
            'message' => $validated['offense_code'] . ' - ' . $title . ' (offense #' . $number . '). Sanction: ' . $sanction['label'],
            'url' => route('report'),
            'type' => 'student_violation',
        ]));
    }

    return redirect()->route('admin.violation.monitoring')
        ->with('success', "Recorded {$validated['offense_code']} (offense #{$number}). Sanction: {$sanction['label']}");
}
}