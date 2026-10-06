<?php

namespace Tests\Feature;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use App\Models\Violation;
use App\Notifications\ViolationStatusUpdated;
use Database\Seeders\MockDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class StudentViolationPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_mock_data_seeder_assigns_admin_role_and_student_profile_fields(): void
    {
        Artisan::call('db:seed', ['--class' => MockDataSeeder::class]);

        $admin = User::where('is_admin', true)->first();
        $student = User::where('role', 'student')->first();

        $this->assertNotNull($admin);
        $this->assertSame('admin', $admin->role);
        $this->assertNotNull($student);
        $this->assertSame('student', $student->role);
        $this->assertNotNull($student->course);
        $this->assertNotNull($student->year_level);
        $this->assertNotNull($student->section);
        $this->assertGreaterThanOrEqual(8, User::where('role', 'student')->count());
        $this->assertGreaterThanOrEqual(1, Violation::where('issued_by', $admin->id)->count());
    }

    public function test_mock_data_seeder_uses_unique_student_names_and_a_limited_violation_count(): void
    {
        Artisan::call('db:seed', ['--class' => MockDataSeeder::class]);

        $students = User::where('role', 'student')->get();

        $this->assertLessThanOrEqual(12, $students->count());
        $this->assertSame($students->count(), $students->pluck('full_name')->unique()->count());
        $this->assertLessThanOrEqual(20, Violation::count());
    }

    public function test_student_login_uses_the_configured_session_lifetime_and_stays_authenticated(): void
    {
        config()->set('session.lifetime', 120);
        config()->set('session.expire_on_close', false);

        $student = User::factory()->create([
            'role' => 'student',
            'name' => 'Student One',
            'email' => 'student@example.com',
            'password' => bcrypt('password123'),
        ]);

        $this->post('/login', [
            'email' => $student->email,
            'password' => 'password123',
            'login_as' => 'student',
        ])->assertRedirect('/dashboard');

        $this->assertAuthenticatedAs($student);
        $this->assertSame(120, config('session.lifetime'));
        $this->assertFalse(config('session.expire_on_close'));
    }

    public function test_student_violation_monitoring_page_loads_for_authenticated_student(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
            'name' => 'Student One',
            'email' => 'student@example.com',
        ]);

        Violation::create([
            'user_id' => $student->id,
            'issued_by' => $student->id,
            'violation_code' => 'V-001',
            'violation_type' => 'Late Arrival',
            'description' => 'Arrived late to class.',
            'occurred_at' => now(),
            'status' => 'pending',
        ]);

        $this->actingAs($student)
            ->get('/violation-monitoring')
            ->assertOk()
            ->assertViewIs('violations.index');
    }

    public function test_student_report_page_shows_real_violation_history_and_statuses(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
            'name' => 'Student Report User',
            'email' => 'report-student@example.com',
        ]);

        Violation::create([
            'user_id' => $student->id,
            'issued_by' => $student->id,
            'violation_code' => 'V-010',
            'violation_type' => 'Late Arrival',
            'description' => 'Arrived late.',
            'occurred_at' => now()->subDays(2),
            'status' => 'resolved',
        ]);

        Violation::create([
            'user_id' => $student->id,
            'issued_by' => $student->id,
            'violation_code' => 'V-011',
            'violation_type' => 'Smoking',
            'description' => 'Smoking in campus.',
            'occurred_at' => now()->subDay(),
            'status' => 'pending',
        ]);

        $this->actingAs($student)
            ->get('/report')
            ->assertOk()
            ->assertSee('Late Arrival')
            ->assertSee('Resolved Case')
            ->assertSee('Smoking')
            ->assertSee('Pending');
    }

    public function test_admin_report_page_loads_live_violation_data(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email' => 'admin-report@example.com',
            'name' => 'Admin Report User',
        ]);

        $student = User::factory()->create([
            'role' => 'student',
            'email' => 'student-report@example.com',
            'name' => 'Student Report Name',
        ]);

        Violation::create([
            'user_id' => $student->id,
            'issued_by' => $admin->id,
            'violation_code' => 'V-900',
            'violation_type' => 'Late Arrival',
            'description' => 'Late',
            'occurred_at' => now()->subDay(),
            'status' => 'resolved',
        ]);

        $this->actingAs($admin)
            ->get('/admin/report')
            ->assertOk()
            ->assertSee('Late Arrival')
            ->assertSee('RESOLVED');
    }

    public function test_admin_student_violation_history_page_shows_full_details(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email' => 'admin-history@example.com',
            'name' => 'Admin History User',
        ]);

        $student = User::factory()->create([
            'role' => 'student',
            'email' => 'student-history@example.com',
            'name' => 'History Student',
            'full_name' => 'History Student',
            'student_id' => 'S-1001',
        ]);

        Violation::create([
            'user_id' => $student->id,
            'issued_by' => $admin->id,
            'violation_code' => 'V-905',
            'violation_type' => 'Absence',
            'description' => 'Student missed class without notice.',
            'occurred_at' => now()->subDays(3),
            'status' => 'pending',
        ]);

        Violation::create([
            'user_id' => $student->id,
            'issued_by' => $admin->id,
            'violation_code' => 'V-906',
            'violation_type' => 'Late',
            'description' => 'Arrived to class 20 minutes late.',
            'occurred_at' => now()->subDay(),
            'status' => 'resolved',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.violations.history', $student->id))
            ->assertOk()
            ->assertSee('History Student')
            ->assertSee('Absence')
            ->assertSee('Late')
            ->assertSee('Violation History');
    }

    public function test_admin_can_mark_violation_as_consequence_applied(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email' => 'admin-consequence@example.com',
            'name' => 'Admin Consequence User',
        ]);

        $student = User::factory()->create([
            'role' => 'student',
            'email' => 'student-consequence@example.com',
            'name' => 'Student Consequence User',
        ]);

        $violation = Violation::create([
            'user_id' => $student->id,
            'issued_by' => $admin->id,
            'violation_code' => 'V-901',
            'violation_type' => 'Smoking',
            'description' => 'Smoking on campus.',
            'occurred_at' => now()->subHours(2),
            'status' => 'pending',
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.violations.approve', $violation))
            ->assertRedirect(route('admin.consequences'));

        $this->assertDatabaseHas('violations', [
            'id' => $violation->id,
            'status' => 'consequence_applied',
        ]);
    }

    public function test_student_can_open_penalty_quiz_for_consequence_violation(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
            'email' => 'student-quiz@example.com',
            'name' => 'Quiz Student',
        ]);

        $violation = Violation::create([
            'user_id' => $student->id,
            'issued_by' => $student->id,
            'violation_code' => 'V-902',
            'violation_type' => 'Uniform Violation',
            'description' => 'Improper uniform.',
            'occurred_at' => now()->subDay(),
            'status' => 'consequence_applied',
        ]);

        $this->actingAs($student)
            ->get(route('student.penalty.quiz', $violation))
            ->assertOk()
            ->assertSee('Penalty Quiz');
    }

    public function test_student_notification_can_be_marked_as_read(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
            'email' => 'student-notification@example.com',
            'name' => 'Notification Student',
        ]);

        Notification::send($student, new ViolationStatusUpdated([
            'title' => 'Violation Recorded',
            'message' => 'A new violation was recorded for your account.',
            'url' => route('report'),
            'type' => 'student_violation',
        ]));

        $notification = $student->unreadNotifications()->first();
        $this->assertNotNull($notification);

        $this->actingAs($student)
            ->post('/notifications/' . $notification->id . '/read')
            ->assertOk()
            ->assertJson(['status' => 'read']);

        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
            'notifiable_id' => $student->id,
        ]);

        $this->assertNull($student->fresh()->unreadNotifications()->first());
    }

    public function test_admin_receives_a_notification_when_student_submits_a_quiz(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email' => 'admin-quiz-alert@example.com',
            'name' => 'Quiz Alert Admin',
        ]);

        $student = User::factory()->create([
            'role' => 'student',
            'email' => 'student-quiz-alert@example.com',
            'name' => 'Quiz Alert Student',
        ]);

        $violation = Violation::create([
            'user_id' => $student->id,
            'issued_by' => $admin->id,
            'violation_code' => 'V-904',
            'violation_type' => 'Late Arrival',
            'description' => 'Late to class.',
            'occurred_at' => now()->subDay(),
            'status' => 'consequence_applied',
        ]);

        $quiz = Quiz::ensureForViolation($violation);
        $question = $quiz->questions()->first();

        $this->actingAs($student)
            ->post(route('student.penalty.quiz.submit', $violation), [
                'question_' . $question->id => 'I understand the rule and will comply going forward.',
            ])
            ->assertRedirect(route('report'));

        $this->assertDatabaseHas('notifications', [
            'notifiable_type' => User::class,
            'notifiable_id' => $admin->id,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.notifications.index'))
            ->assertOk()
            ->assertSee('Quiz submission');
    }

    public function test_student_reasoning_submission_and_admin_appeal_review_workflow(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email' => 'admin-appeal@example.com',
            'name' => 'Admin Appeal User',
        ]);

        $student = User::factory()->create([
            'role' => 'student',
            'email' => 'student-appeal@example.com',
            'name' => 'Appeal Student',
        ]);

        $violation = Violation::create([
            'user_id' => $student->id,
            'issued_by' => $admin->id,
            'violation_code' => 'V-903',
            'violation_type' => 'Dress Code',
            'description' => 'Uniform violation.',
            'occurred_at' => now()->subDay(),
            'status' => 'consequence_applied',
        ]);

        $quiz = Quiz::ensureForViolation($violation);
        $question = $quiz->questions()->first();

        $this->actingAs($student)
            ->post(route('student.penalty.quiz.submit', $violation), [
                'question_' . $question->id => 'I was rushing to class and I understand the rule now. I will comply in the future.',
            ])
            ->assertRedirect(route('report'));

        $attempt = QuizAttempt::query()->where('user_id', $student->id)->latest()->first();
        $this->assertNotNull($attempt);
        $this->assertSame('I was rushing to class and I understand the rule now. I will comply in the future.', $attempt->answers[$question->id] ?? '');

        $this->actingAs($admin)
            ->get('/admin/appeals')
            ->assertOk()
            ->assertSee('Appeal Student');

        $this->actingAs($admin)
            ->patch(route('admin.appeals.review', $attempt), [
                'approved' => true,
                'admin_note' => 'Reasoning accepted.',
            ])
            ->assertRedirect(route('admin.appeals'));

        $attempt->refresh();
        $this->assertTrue((bool) $attempt->admin_approved);
        $this->assertDatabaseHas('violations', [
            'id' => $violation->id,
            'status' => 'resolved',
        ]);
    }

    public function test_admin_violation_list_filters_by_course_and_section(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email' => 'admin@example.com',
            'name' => 'Admin User',
        ]);

        $matchedStudent = User::factory()->create([
            'course' => 'BSIT',
            'section' => 'A',
            'name' => 'Matched Student',
        ]);

        $otherStudent = User::factory()->create([
            'course' => 'BSCS',
            'section' => 'B',
            'name' => 'Other Student',
        ]);

        Violation::create([
            'user_id' => $matchedStudent->id,
            'issued_by' => $admin->id,
            'violation_code' => 'V-101',
            'violation_type' => 'Late Arrival',
            'description' => 'Matched case.',
            'occurred_at' => now()->subDay(),
            'status' => 'pending',
        ]);

        Violation::create([
            'user_id' => $otherStudent->id,
            'issued_by' => $admin->id,
            'violation_code' => 'V-202',
            'violation_type' => 'Cheating',
            'description' => 'Other case.',
            'occurred_at' => now()->subDay(),
            'status' => 'resolved',
        ]);

        $this->actingAs($admin)
            ->get('/admin/violations?course=BSIT&section=A')
            ->assertOk()
            ->assertSee('V-101')
            ->assertDontSee('V-202');
    }

    public function test_admin_violation_list_filters_by_year_level(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email' => 'admin-year-level@example.com',
            'name' => 'Admin Year User',
        ]);

        $matchedStudent = User::factory()->create([
            'course' => 'BS Information Technology',
            'section' => '101',
            'year_level' => '3rd Year',
            'name' => 'Year Level Matched Student',
        ]);

        $otherStudent = User::factory()->create([
            'course' => 'BS Accountancy',
            'section' => '202',
            'year_level' => '2nd Year',
            'name' => 'Year Level Other Student',
        ]);

        Violation::create([
            'user_id' => $matchedStudent->id,
            'issued_by' => $admin->id,
            'violation_code' => 'V-301',
            'violation_type' => 'Late Arrival',
            'description' => 'Year level matched case.',
            'occurred_at' => now()->subDay(),
            'status' => 'pending',
        ]);

        Violation::create([
            'user_id' => $otherStudent->id,
            'issued_by' => $admin->id,
            'violation_code' => 'V-302',
            'violation_type' => 'Cheating',
            'description' => 'Other year level case.',
            'occurred_at' => now()->subDay(),
            'status' => 'resolved',
        ]);

        $this->actingAs($admin)
            ->get('/admin/violations?year_level=3rd+Year')
            ->assertOk()
            ->assertSee('V-301')
            ->assertDontSee('V-302');
    }
}