<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Violation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_dashboard_exposes_real_lifecycle_summary(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
            'email' => 'student.dashboard@example.com',
            'full_name' => 'Student Dashboard User',
        ]);

        Violation::create([
            'user_id' => $student->id,
            'issued_by' => $student->id,
            'violation_code' => 'V-201',
            'violation_type' => 'Late Arrival',
            'description' => 'Late arrival',
            'occurred_at' => now()->subDays(2),
            'status' => 'pending',
            'offense_code' => 'MI1',
            'offense_category' => 'minor',
        ]);

        Violation::create([
            'user_id' => $student->id,
            'issued_by' => $student->id,
            'violation_code' => 'V-202',
            'violation_type' => 'Harassment',
            'description' => 'Student misconduct',
            'occurred_at' => now()->subDay(),
            'status' => 'consequence_applied',
            'offense_code' => 'MA1',
            'offense_category' => 'major',
        ]);

        Violation::create([
            'user_id' => $student->id,
            'issued_by' => $student->id,
            'violation_code' => 'V-203',
            'violation_type' => 'Uniform',
            'description' => 'Uniform issue',
            'occurred_at' => now(),
            'status' => 'resolved',
            'offense_code' => 'MI2',
            'offense_category' => 'minor',
        ]);

        $this->actingAs($student)
            ->get('/dashboard')
            ->assertOk()
            ->assertViewHas('summary', function (array $summary) {
                return $summary['total'] === 3
                    && $summary['active'] === 1
                    && $summary['pending'] === 1
                    && $summary['resolved'] === 1
                    && $summary['major'] === 1
                    && $summary['minor'] === 2;
            });
    }

    public function test_admin_dashboard_exposes_operational_overview(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email' => 'admin.dashboard@example.com',
            'full_name' => 'Admin Dashboard User',
        ]);

        $student = User::factory()->create([
            'role' => 'student',
            'email' => 'student.ops@example.com',
            'full_name' => 'Ops Student',
        ]);

        Violation::create([
            'user_id' => $student->id,
            'issued_by' => $admin->id,
            'violation_code' => 'V-301',
            'violation_type' => 'Late Arrival',
            'description' => 'Late',
            'occurred_at' => now()->subDays(3),
            'status' => 'pending',
            'offense_code' => 'MI4',
            'offense_category' => 'minor',
        ]);

        Violation::create([
            'user_id' => $student->id,
            'issued_by' => $admin->id,
            'violation_code' => 'V-302',
            'violation_type' => 'Serious Misconduct',
            'description' => 'Major issue',
            'occurred_at' => now()->subDays(1),
            'status' => 'consequence_applied',
            'offense_code' => 'MA2',
            'offense_category' => 'major',
        ]);

        $this->actingAs($admin)
            ->get('/admin/dashboard')
            ->assertOk()
            ->assertViewHas('stats', function (array $stats) {
                return $stats['total'] === 2
                    && $stats['active'] === 1
                    && $stats['pending'] === 1
                    && $stats['resolved'] === 0
                    && $stats['major'] === 1
                    && $stats['minor'] === 1;
            });
    }

    public function test_admin_violation_index_displays_management_summary_and_review_actions(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email' => 'admin.management@example.com',
            'full_name' => 'Admin Management User',
        ]);

        $student = User::factory()->create([
            'role' => 'student',
            'email' => 'student.management@example.com',
            'full_name' => 'Management Student',
            'student_id' => 'S-2001',
        ]);

        Violation::create([
            'user_id' => $student->id,
            'issued_by' => $admin->id,
            'violation_code' => 'V-401',
            'violation_type' => 'Late Arrival',
            'description' => 'Arrived late on Friday.',
            'occurred_at' => now()->subDay(),
            'status' => 'pending',
            'offense_code' => 'MI2',
            'offense_category' => 'minor',
        ]);

        $this->actingAs($admin)
            ->get('/admin/violations')
            ->assertOk()
            ->assertViewHas('summary', function (array $summary) {
                return $summary['total'] === 1
                    && $summary['active'] === 1
                    && $summary['pending'] === 0
                    && $summary['resolved'] === 0;
            })
            ->assertSee('Review case')
            ->assertSee('Case management');
    }

    public function test_admin_can_escalate_high_risk_case_and_track_case_ownership(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email' => 'admin.escalation@example.com',
            'full_name' => 'Escalation Admin',
        ]);

        $student = User::factory()->create([
            'role' => 'student',
            'email' => 'student.escalation@example.com',
            'full_name' => 'Escalation Student',
            'student_id' => 'S-3010',
        ]);

        $violation = Violation::create([
            'user_id' => $student->id,
            'issued_by' => $admin->id,
            'violation_code' => 'V-501',
            'violation_type' => 'Serious misconduct',
            'description' => 'Repeated disregard of campus discipline rules.',
            'occurred_at' => now()->subDays(2),
            'status' => 'pending',
            'offense_code' => 'MA1',
            'offense_category' => 'major',
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.violations.escalate', $violation), [
                'reason' => 'Repeat offense after prior warning',
            ])
            ->assertRedirect(route('admin.violations.index'));

        $violation->refresh();

        $this->assertSame($admin->id, $violation->case_owner_id);
        $this->assertSame($admin->id, $violation->last_updated_by);
        $this->assertSame('escalated', $violation->review_level);
        $this->assertSame('Repeat offense after prior warning', $violation->escalation_reason);
        $this->assertNotNull($violation->escalated_at);
    }
}
