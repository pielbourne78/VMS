<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Violation;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
