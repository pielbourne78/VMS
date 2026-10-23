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

    public function test_student_penalty_quiz_requires_all_reasoning_answers(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
            'email' => 'student-quiz-validation@example.com',
            'name' => 'Student Quiz Validation',
        ]);

        $violation = Violation::create([
            'user_id' => $student->id,
            'issued_by' => User::factory()->create(['is_admin' => true])->id,
            'violation_code' => 'V-950',
            'violation_type' => 'Late Arrival',
            'description' => 'Student arrived after class started.',
            'occurred_at' => now()->subDay(),
            'status' => 'pending',
        ]);

        $this->actingAs($student)
            ->from(route('student.penalty.quiz', $violation))
            ->post(route('student.penalty.quiz.submit', $violation), [])
            ->assertSessionHasErrors('question_1');
    }

    public function test_admin_consequence_approval_rejects_non_pending_cases(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email' => 'admin-consequence-validation@example.com',
            'name' => 'Admin Consequence Validation',
        ]);

        $student = User::factory()->create([
            'role' => 'student',
            'email' => 'student-consequence-validation@example.com',
            'name' => 'Student Consequence Validation',
        ]);

        $violation = Violation::create([
            'user_id' => $student->id,
            'issued_by' => $admin->id,
            'violation_code' => 'V-960',
            'violation_type' => 'Absence',
            'description' => 'Student missed class without notification.',
            'occurred_at' => now()->subDay(),
            'status' => 'resolved',
            'resolved_at' => now(),
        ]);

        $this->actingAs($admin)
            ->from(route('admin.consequences'))
            ->patch(route('admin.violations.approve', $violation), [])
            ->assertSessionHasErrors('status');
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

    public function test_admin_violation_history_uses_offense_code_as_case_id_and_shows_sanction(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email' => 'admin-case-id@example.com',
            'name' => 'Admin Case ID User',
        ]);

        $student = User::factory()->create([
            'role' => 'student',
            'email' => 'student-case-id@example.com',
            'name' => 'Case ID Student',
            'full_name' => 'Case ID Student',
            'student_id' => 'S-1101',
        ]);

        Violation::create([
            'user_id' => $student->id,
            'issued_by' => $admin->id,
            'violation_code' => 'MA1-01',
            'violation_type' => 'Sexual harassment / acts of lasciviousness',
            'offense_code' => 'MA1',
            'offense_category' => 'major',
            'offense_number' => 1,
            'sanction_code' => 'D',
            'sanction_label' => 'D - Non-readmission',
            'description' => 'Student committed a serious offense.',
            'occurred_at' => now()->subDays(2),
            'status' => 'pending',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.violations.history', $student->id))
            ->assertOk()
            ->assertSee('MA1')
            ->assertSee('D - Non-readmission');
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

    public function test_penalty_quiz_prompt_avoids_banned_phrase_and_uses_clearer_language(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
            'email' => 'student-quiz-prompt@example.com',
            'name' => 'Prompt Student',
        ]);

        $violation = Violation::create([
            'user_id' => $student->id,
            'issued_by' => $student->id,
            'violation_code' => 'V-905',
            'violation_type' => 'Late Arrival',
            'description' => 'Late to class.',
            'occurred_at' => now()->subDay(),
            'status' => 'consequence_applied',
        ]);

        $quiz = Quiz::ensureForViolation($violation);
        $prompt = $quiz->questions()->first()->prompt;

        $this->assertStringNotContainsString('moving forward', strtolower($prompt));
        $this->assertStringContainsString('attendance rules', $prompt);
        $this->assertStringContainsString('future violations', $prompt);
    }

    public function test_existing_quiz_prompt_is_normalized_when_it_has_old_banned_phrase(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
            'email' => 'student-quiz-normalize@example.com',
            'name' => 'Normalize Student',
        ]);

        $violation = Violation::create([
            'user_id' => $student->id,
            'issued_by' => $student->id,
            'violation_code' => 'V-906',
            'violation_type' => 'Late Arrival',
            'description' => 'Late to class.',
            'occurred_at' => now()->subDay(),
            'status' => 'consequence_applied',
        ]);

        $quiz = Quiz::create([
            'violation_id' => $violation->id,
            'title' => 'Penalty Quiz',
            'description' => 'This quiz measures understanding of the campus rules related to the reported violation.',
            'passing_score' => 70,
        ]);

        $quiz->questions()->create([
            'prompt' => 'Describe the circumstances that led to your late arrival and explain how you will comply with campus time rules moving forward.',
            'options' => [],
            'correct_option' => 'reasoning-submission-required',
            'points' => 1,
        ]);

        $normalized = Quiz::ensureForViolation($violation);
        $prompt = $normalized->questions()->first()->prompt;

        $this->assertStringNotContainsString('moving forward', strtolower($prompt));
        $this->assertStringContainsString('attendance rules', $prompt);
        $this->assertStringContainsString('future violations', $prompt);
    }

    public function test_student_dashboard_only_shows_sanction_warning_notifications(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
            'email' => 'student-warning@example.com',
            'name' => 'Warning Student',
        ]);

        Notification::send($student, new ViolationStatusUpdated([
            'title' => 'Sanction Warning',
            'message' => 'Warning: sanctions charged — D - Non-readmission',
            'url' => route('report'),
            'type' => 'migration_warning',
            'sanction' => 'D - Non-readmission',
        ]));

        Notification::send($student, new ViolationStatusUpdated([
            'title' => 'Consequence Applied',
            'message' => 'A consequence was applied to your case.',
            'url' => route('student.penalty.quiz', 1),
            'type' => 'consequence_applied',
            'sanction' => 'D - Non-readmission',
        ]));

        $this->actingAs($student)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('Sanction Warning')
            ->assertSee('D - Non-readmission')
            ->assertDontSee('Consequence Applied');
    }

    public function test_student_dashboard_handles_legacy_migration_warning_without_sanction_field(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
            'email' => 'legacy-warning@example.com',
            'name' => 'Legacy Warning Student',
        ]);

        Notification::send($student, new ViolationStatusUpdated([
            'title' => 'Warning',
            'message' => 'A migration sanction warning has been issued for this case.',
            'url' => route('report'),
            'type' => 'migration_warning',
        ]));

        $this->actingAs($student)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('Warning')
            ->assertSee('A migration sanction warning has been issued for this case.');
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

    public function test_admin_appeal_history_shows_pending_cases_and_supports_explicit_decisions(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email' => 'admin-appeal-history@example.com',
            'name' => 'Admin Appeal History User',
        ]);

        $student = User::factory()->create([
            'role' => 'student',
            'email' => 'student-appeal-history@example.com',
            'name' => 'History Appeal Student',
        ]);

        $violation = Violation::create([
            'user_id' => $student->id,
            'issued_by' => $admin->id,
            'violation_code' => 'V-904',
            'violation_type' => 'Late Entry',
            'description' => 'Student arrived late to class.',
            'occurred_at' => now()->subDay(),
            'status' => 'consequence_applied',
        ]);

        $quiz = Quiz::ensureForViolation($violation);
        $question = $quiz->questions()->first();

        $this->actingAs($student)
            ->post(route('student.penalty.quiz.submit', $violation), [
                'question_' . $question->id => 'I arrived late due to transport issues and I understand the consequences.',
            ])
            ->assertRedirect(route('report'));

        $attempt = QuizAttempt::query()->where('user_id', $student->id)->latest()->first();
        $this->assertNotNull($attempt);

        $this->actingAs($admin)
            ->get('/admin/appeals')
            ->assertOk()
            ->assertSee('Appeal History')
            ->assertSee('Pending')
            ->assertSee('Approve')
            ->assertSee('Decline');

        $this->actingAs($admin)
            ->patch(route('admin.appeals.decline', $attempt), [
                'admin_note' => 'The explanation is not enough to reverse the consequence.',
            ])
            ->assertRedirect(route('admin.appeals'));

        $attempt->refresh();
        $this->assertFalse((bool) $attempt->admin_approved);
        $this->assertSame('The explanation is not enough to reverse the consequence.', $attempt->admin_note);
        $this->assertDatabaseHas('violations', [
            'id' => $violation->id,
            'status' => 'pending',
        ]);
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

    public function test_approved_appeal_creates_admin_report_record_for_appeal_ledger(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email' => 'admin-appeal-report@example.com',
            'name' => 'Admin Appeal Report User',
        ]);

        $student = User::factory()->create([
            'role' => 'student',
            'email' => 'report-appeal-student@example.com',
            'name' => 'Report Appeal Student',
        ]);

        $violation = Violation::create([
            'user_id' => $student->id,
            'issued_by' => $admin->id,
            'violation_code' => 'V-970',
            'violation_type' => 'Unauthorized Use',
            'description' => 'Using a device during class.',
            'occurred_at' => now()->subDay(),
            'status' => 'consequence_applied',
        ]);

        $quiz = Quiz::ensureForViolation($violation);
        $question = $quiz->questions()->first();

        $this->actingAs($student)
            ->post(route('student.penalty.quiz.submit', $violation), [
                'question_' . $question->id => 'I understand the policy and will comply going forward.',
            ]);

        $attempt = QuizAttempt::query()->where('user_id', $student->id)->latest()->first();

        $this->actingAs($admin)
            ->patch(route('admin.appeals.approve', $attempt), [
                'admin_note' => 'Reasoning accepted after review.',
            ])
            ->assertRedirect(route('admin.appeals'));

        $this->assertTrue(
            $admin->fresh()->notifications()
                ->where('data->type', 'appeal_decision')
                ->where('data->title', 'Reasoning Approved')
                ->exists()
        );

        $response = $this->actingAs($admin)->get('/admin/appeal-reports');
        $response->assertOk();
        $response->assertSee('Reasoning Approved');
    }

    public function test_admin_appeal_report_exposes_read_and_unread_controls(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email' => 'admin-appeal-report-controls@example.com',
            'name' => 'Admin Appeal Report Controls User',
        ]);

        $student = User::factory()->create([
            'role' => 'student',
            'email' => 'control-appeal-student@example.com',
            'name' => 'Control Appeal Student',
        ]);

        $violation = Violation::create([
            'user_id' => $student->id,
            'issued_by' => $admin->id,
            'violation_code' => 'V-980',
            'violation_type' => 'Unauthorized Use',
            'description' => 'Using a device during class.',
            'occurred_at' => now()->subDay(),
            'status' => 'consequence_applied',
        ]);

        $quiz = Quiz::ensureForViolation($violation);
        $question = $quiz->questions()->first();

        $this->actingAs($student)
            ->post(route('student.penalty.quiz.submit', $violation), [
                'question_' . $question->id => 'I understand the policy and will comply going forward.',
            ]);

        $attempt = QuizAttempt::query()->where('user_id', $student->id)->latest()->first();

        $this->actingAs($admin)
            ->patch(route('admin.appeals.approve', $attempt), [
                'admin_note' => 'Reasoning accepted after review.',
            ]);

        $this->actingAs($admin)
            ->get('/admin/appeal-reports')
            ->assertOk()
            ->assertSee('data-status-badge')
            ->assertSee('data-toggle-read-button');
    }

    public function test_admin_appeal_report_summary_counts_include_all_records_not_just_current_page(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email' => 'admin-appeal-summary-report@example.com',
            'name' => 'Admin Appeal Summary Report User',
        ]);

        for ($i = 0; $i < 18; $i++) {
            $status = $i % 3 === 0 ? 'approved' : ($i % 3 === 1 ? 'declined' : 'pending');
            $title = $status === 'approved' ? 'Reasoning Approved' : ($status === 'declined' ? 'Reasoning Review Pending' : 'Penalty quiz submitted');
            $message = $status === 'approved'
                ? 'Your reasoning was approved and your violation case has been resolved.'
                : ($status === 'declined'
                    ? 'Your reasoning was not approved. The case remains active for further review.'
                    : 'A student submitted a penalty quiz for review.');

            \Illuminate\Support\Facades\DB::table('notifications')->insert([
                'id' => \Illuminate\Support\Str::uuid(),
                'type' => 'App\\Notifications\\ViolationStatusUpdated',
                'notifiable_type' => User::class,
                'notifiable_id' => $admin->id,
                'data' => json_encode([
                    'title' => $title,
                    'message' => $message,
                    'url' => route('admin.appeals'),
                    'type' => $status === 'pending' ? 'student_quiz_submission' : 'appeal_decision',
                    'created_at' => now()->toDateTimeString(),
                ]),
                'read_at' => $i % 2 === 0 ? null : now()->subHours($i + 1),
                'created_at' => now()->subMinutes($i + 1),
                'updated_at' => now(),
            ]);
        }

        $response = $this->actingAs($admin)->get('/admin/appeal-reports?page=1');
        $response->assertOk();

        $summary = $response->getOriginalContent()->getData()['reportSummary'] ?? [];

        $this->assertSame(18, $summary['total'] ?? 0);
        $this->assertSame(6, $summary['approved'] ?? 0);
        $this->assertSame(6, $summary['declined'] ?? 0);
        $this->assertSame(9, $summary['unread'] ?? 0);
    }

    public function test_admin_appeal_summary_counts_approved_and_disapproved_cases(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email' => 'admin-appeal-summary@example.com',
            'name' => 'Admin Appeal Summary User',
        ]);

        $student = User::factory()->create([
            'role' => 'student',
            'email' => 'summary-appeal-student@example.com',
            'name' => 'Summary Appeal Student',
        ]);

        $pendingViolation = Violation::create([
            'user_id' => $student->id,
            'issued_by' => $admin->id,
            'violation_code' => 'V-960',
            'violation_type' => 'Unauthorized Use',
            'description' => 'Using a device during class.',
            'occurred_at' => now()->subDay(),
            'status' => 'consequence_applied',
        ]);

        $quiz = Quiz::ensureForViolation($pendingViolation);
        $question = $quiz->questions()->first();

        $this->actingAs($student)
            ->post(route('student.penalty.quiz.submit', $pendingViolation), [
                'question_' . $question->id => 'I understand the policy and will comply in the future.',
            ]);

        $attempt = QuizAttempt::query()->where('user_id', $student->id)->latest()->first();
        $this->assertNotNull($attempt);

        $this->actingAs($admin)
            ->patch(route('admin.appeals.approve', $attempt), [
                'admin_note' => 'Reasoning accepted after review.',
            ])
            ->assertRedirect(route('admin.appeals'));

        $response = $this->actingAs($admin)->get('/admin/appeals');
        $response->assertOk();

        $viewData = $response->getOriginalContent()->getData();
        $this->assertSame(1, $viewData['summary']['approved']);
        $this->assertSame(0, $viewData['summary']['pending']);
        $this->assertSame(1, $viewData['summary']['total']);
    }

    public function test_admin_appeals_queue_only_shows_pending_cases_and_filters_by_student_identifier(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email' => 'admin-appeal-queue@example.com',
            'name' => 'Admin Appeal Queue User',
        ]);

        $pendingStudent = User::factory()->create([
            'role' => 'student',
            'email' => 'pending-appeal-student@example.com',
            'name' => 'Pending Appeal Student',
            'student_id' => 'STU-1001',
        ]);

        $resolvedStudent = User::factory()->create([
            'role' => 'student',
            'email' => 'resolved-appeal-student@example.com',
            'name' => 'Resolved Appeal Student',
            'student_id' => 'STU-2002',
        ]);

        $pendingViolation = Violation::create([
            'user_id' => $pendingStudent->id,
            'issued_by' => $admin->id,
            'violation_code' => 'V-950',
            'violation_type' => 'Unauthorized Use',
            'description' => 'Using a device during class.',
            'occurred_at' => now()->subDay(),
            'status' => 'consequence_applied',
        ]);

        $resolvedViolation = Violation::create([
            'user_id' => $resolvedStudent->id,
            'issued_by' => $admin->id,
            'violation_code' => 'V-951',
            'violation_type' => 'Smoking',
            'description' => 'Smoking on campus.',
            'occurred_at' => now()->subDays(2),
            'status' => 'resolved',
        ]);

        $pendingQuiz = Quiz::ensureForViolation($pendingViolation);
        $resolvedQuiz = Quiz::ensureForViolation($resolvedViolation);

        $pendingQuestion = $pendingQuiz->questions()->first();
        $resolvedQuestion = $resolvedQuiz->questions()->first();

        $this->actingAs($pendingStudent)
            ->post(route('student.penalty.quiz.submit', $pendingViolation), [
                'question_' . $pendingQuestion->id => 'I understand the policy and will comply going forward.',
            ]);

        $this->actingAs($resolvedStudent)
            ->post(route('student.penalty.quiz.submit', $resolvedViolation), [
                'question_' . $resolvedQuestion->id => 'I was not aware of the rule and I will avoid it.',
            ]);

        $resolvedAttempt = QuizAttempt::query()->where('user_id', $resolvedStudent->id)->latest()->first();
        $this->actingAs($admin)
            ->patch(route('admin.appeals.approve', $resolvedAttempt), [
                'admin_note' => 'Reasoning accepted after review.',
            ])
            ->assertRedirect(route('admin.appeals'));

        $this->actingAs($admin)
            ->get('/admin/appeals')
            ->assertOk()
            ->assertSee('Pending Appeal Student')
            ->assertDontSee('Resolved Appeal Student');

        $this->actingAs($admin)
            ->get('/admin/appeals?search=STU-1001')
            ->assertOk()
            ->assertSee('Pending Appeal Student')
            ->assertDontSee('Resolved Appeal Student');
    }

    public function test_admin_mock_appeal_approval_updates_the_mock_summary(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email' => 'admin-mock-appeal@example.com',
            'name' => 'Admin Mock Appeal User',
        ]);

        $this->actingAs($admin)
            ->get('/admin/appeals')
            ->assertOk();

        $this->actingAs($admin)
            ->patch(route('admin.appeals.approve', 201), [
                'admin_note' => 'Mock appeal approved for summary verification.',
            ])
            ->assertRedirect(route('admin.appeals'));

        $response = $this->actingAs($admin)->get('/admin/appeals');
        $response->assertOk();

        $viewData = $response->getOriginalContent()->getData();
        $this->assertSame(2, $viewData['summary']['approved']);
        $this->assertSame(2, $viewData['summary']['pending']);
        $this->assertSame(4, $viewData['summary']['total']);
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