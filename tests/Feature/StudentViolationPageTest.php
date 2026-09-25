<?php

namespace Tests\Feature;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use App\Models\Violation;
use App\Notifications\ViolationStatusUpdated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class StudentViolationPageTest extends TestCase
{
    use RefreshDatabase;

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
}