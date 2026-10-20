<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentLayoutRegressionTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_dashboard_has_balanced_layout_markup_and_student_sidebar_items(): void
    {
        $user = User::factory()->create([
            'name' => 'Student User',
            'email' => 'student@example.com',
            'student_id' => 'S-2024-001',
            'course' => 'BS Information Technology',
            'year_level' => '3rd Year',
            'section' => 'A',
            'is_admin' => false,
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        $html = $response->getContent();

        $this->assertSame(1, substr_count($html, '<header'));
        $this->assertSame(1, substr_count($html, '</header>'));
        $this->assertStringContainsString('<div class="dashboard-shell relative min-h-screen">', $html);
        $this->assertStringContainsString('id="sidebar"', $html);
        $this->assertStringContainsString('id="sidebarOpenBtn"', $html);
        $this->assertStringContainsString('ARTICLES VI CODE OF DISCIPLINE', $html);
        $this->assertStringContainsString('LOG OUT', $html);
        $this->assertStringContainsString('BS Information Technology', $html);
    }
}
