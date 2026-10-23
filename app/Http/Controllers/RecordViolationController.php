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
    $offenses = $this->buildActiveOffenses();
    $counts = $this->buildOffenseCounts();
    $locations = $this->getLocations();

    return view('admin.violation-monitoring', compact('students', 'offenses', 'counts', 'locations'));
}

public function store(Request $request)
{
    $validated = $this->validateViolationInput($request);
    $offense = $this->resolveOffense($validated['offense_code']);
    $number = $this->resolveOffenseNumber($validated['user_id'], $validated['offense_code']);
    $sanction = Violation::sanctionFor($validated['offense_code'], $number);

    $violation = Violation::create($this->buildViolationPayload($validated, $offense, $number, $sanction));

    $student = User::find($violation->user_id);
    if ($student instanceof User) {
        $student->notify(new ViolationStatusUpdated($this->buildViolationNotificationPayload($validated, $offense->title, $number, $sanction)));
    }

    return redirect()->route('admin.violation.monitoring')
        ->with('success', "Recorded {$validated['offense_code']} (offense #{$number}). Sanction: {$sanction['label']}");
}

   protected function buildActiveOffenses(): array
   {
       return Offense::where('is_active', true)
           ->orderBy('id')
           ->get()
           ->mapWithKeys(fn ($offense) => [$offense->code => [$offense->category, $offense->title, $offense->sanction_path]])
           ->all();
   }

   protected function buildOffenseCounts()
   {
       return Violation::where('status', '!=', 'dismissed')
           ->whereNotNull('offense_code')
           ->selectRaw('user_id, offense_code, COUNT(*) as total')
           ->groupBy('user_id', 'offense_code')
           ->get()
           ->groupBy('user_id')
           ->map(fn ($rows) => $rows->pluck('total', 'offense_code'));
   }

   protected function getLocations(): array
   {
       return ['Building A','Building B','Canteen','Library','Parking Area','Gymnasium','Classroom'];
   }

   protected function validateViolationInput(Request $request): array
   {
       return $request->validate([
           'user_id' => ['required', 'exists:users,id'],
           'offense_code' => ['required', Rule::exists('offenses', 'code')->where('is_active', true)],
           'occurred_at' => ['required', 'date'],
           'location' => ['required', 'string', 'max:255', Rule::in($this->getLocations())],
           'description' => ['required', 'string', 'min:3', 'max:1000'],
       ]);
   }

   protected function resolveOffense(string $offenseCode)
   {
       return Offense::where('code', $offenseCode)->firstOrFail();
   }

   protected function resolveOffenseNumber(int $userId, string $offenseCode): int
   {
       return Violation::where('user_id', $userId)
           ->where('offense_code', $offenseCode)
           ->where('status', '!=', 'dismissed')
           ->count() + 1;
   }

   protected function buildViolationPayload(array $validated, $offense, int $number, array $sanction): array
   {
       return [
           'violation_code' => Violation::caseIdFor($validated['offense_code'], $number, $validated['user_id']),
           'user_id' => $validated['user_id'],
           'issued_by' => Auth::id(),
           'violation_type' => $offense->title,
           'offense_code' => $validated['offense_code'],
           'offense_category' => $offense->category,
           'offense_number' => $number,
           'sanction_code' => $sanction['letter'],
           'sanction_label' => $sanction['label'],
           'beyond_path' => $sanction['beyond'],
           'description' => $validated['location'] . ' — ' . $validated['description'],
           'occurred_at' => $validated['occurred_at'],
           'status' => 'pending',
       ];
   }

   protected function buildViolationNotificationPayload(array $validated, string $title, int $number, array $sanction): array
   {
       return [
           'title' => 'Violation Recorded',
           'message' => $validated['offense_code'] . ' - ' . $title . ' (offense #' . $number . '). Sanction: ' . $sanction['label'],
           'url' => route('report'),
           'type' => 'student_violation',
       ];
   }
}