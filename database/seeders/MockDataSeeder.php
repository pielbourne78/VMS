<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Violation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MockDataSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            'BS Business Administration - Human Resources Dev\'t Mgt.',
            'BS Business Administration - Marketing Management',
            'BS Entrepreneurship',
            'BS Accountancy',
            'BS Information Technology',
            'BS Secondary Education - English',
            'BS Secondary Education - Mathematics',
            'BS Secondary Education - PE',
            'BS Elementary Education - SPEd',
            'Computer Hardware Servicing NCII',
        ];

        $admin = User::firstOrCreate(
            ['email' => 'admin@grc.edu.ph'],
            [
                'name' => 'System Admin',
                'full_name' => 'System Admin',
                'student_id' => 'ADMIN-001',
                'role' => 'admin',
                'password' => Hash::make('password123'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        $admin->update([
            'name' => 'System Admin',
            'full_name' => 'System Admin',
            'role' => 'admin',
            'is_admin' => true,
        ]);

        $students = [
            ['full_name' => 'Maria Isabel Dela Cruz', 'email' => 'maria.delacruz@grc.edu.ph', 'student_id' => '2024-0001', 'course' => $courses[0], 'year_level' => '3rd Year', 'section' => '301'],
            ['full_name' => 'Juan Carlos Santos', 'email' => 'juan.santos@grc.edu.ph', 'student_id' => '2024-0002', 'course' => $courses[1], 'year_level' => '2nd Year', 'section' => '204'],
            ['full_name' => 'Andrea Marie Ramos', 'email' => 'andrea.ramos@grc.edu.ph', 'student_id' => '2024-0003', 'course' => $courses[2], 'year_level' => '1st Year', 'section' => '101'],
            ['full_name' => 'Christian Paolo Reyes', 'email' => 'christian.reyes@grc.edu.ph', 'student_id' => '2024-0004', 'course' => $courses[3], 'year_level' => '4th Year', 'section' => '405'],
            ['full_name' => 'Angelica Joy Garcia', 'email' => 'angelica.garcia@grc.edu.ph', 'student_id' => '2024-0005', 'course' => $courses[4], 'year_level' => '3rd Year', 'section' => '303'],
            ['full_name' => 'Mark Daniel Cruz', 'email' => 'mark.cruz@grc.edu.ph', 'student_id' => '2024-0006', 'course' => $courses[5], 'year_level' => '2nd Year', 'section' => '206'],
            ['full_name' => 'Samantha Grace Mendoza', 'email' => 'samantha.mendoza@grc.edu.ph', 'student_id' => '2024-0007', 'course' => $courses[6], 'year_level' => '1st Year', 'section' => '102'],
            ['full_name' => 'Nathaniel James Lim', 'email' => 'nathaniel.lim@grc.edu.ph', 'student_id' => '2024-0008', 'course' => $courses[7], 'year_level' => '4th Year', 'section' => '402'],
            ['full_name' => 'Patricia Anne Bautista', 'email' => 'patricia.bautista@grc.edu.ph', 'student_id' => '2024-0009', 'course' => $courses[8], 'year_level' => '3rd Year', 'section' => '305'],
            ['full_name' => 'Gabriel Luis Aquino', 'email' => 'gabriel.aquino@grc.edu.ph', 'student_id' => '2024-0010', 'course' => $courses[9], 'year_level' => '2nd Year', 'section' => '203'],
            ['full_name' => 'Kristine Joy Villanueva', 'email' => 'kristine.villanueva@grc.edu.ph', 'student_id' => '2024-0011', 'course' => $courses[0], 'year_level' => '1st Year', 'section' => '103'],
            ['full_name' => 'Rommel Dela Rosa', 'email' => 'rommel.delarosa@grc.edu.ph', 'student_id' => '2024-0012', 'course' => $courses[1], 'year_level' => '3rd Year', 'section' => '304'],
        ];

        foreach ($students as $index => $studentData) {
            $student = User::updateOrCreate(
                ['email' => $studentData['email']],
                [
                    'name' => $studentData['full_name'],
                    'full_name' => $studentData['full_name'],
                    'student_id' => $studentData['student_id'],
                    'role' => 'student',
                    'course' => $studentData['course'],
                    'year_level' => $studentData['year_level'],
                    'section' => $studentData['section'],
                    'password' => Hash::make('password123'),
                    'is_admin' => false,
                    'email_verified_at' => now(),
                ]
            );

            $violationTemplates = [
                [
                    'type' => 'Late',
                    'status' => $index % 3 === 0 ? 'consequence_applied' : 'pending',
                    'description' => 'Student arrived after the scheduled class time.',
                ],
                [
                    'type' => 'Absence',
                    'status' => $index % 2 === 0 ? 'resolved' : 'pending',
                    'description' => 'Student was absent without valid excuse.',
                ],
                [
                    'type' => 'Uniform Violation',
                    'status' => 'pending',
                    'description' => 'Student was not in proper school uniform.',
                ],
            ];

            $recordsToCreate = $index % 2 === 0 ? 2 : 1;

            for ($n = 0; $n < $recordsToCreate; $n++) {
                $violation = $violationTemplates[($index + $n) % count($violationTemplates)];
                $violationCode = 'VIO-' . strtoupper($studentData['student_id']) . '-' . ($n + 1);

                $violationRecord = Violation::firstOrCreate(
                    [
                        'user_id' => $student->id,
                        'violation_code' => $violationCode,
                    ],
                    [
                        'issued_by' => $admin->id,
                        'violation_type' => $violation['type'],
                        'description' => $violation['description'],
                        'status' => $violation['status'],
                        'occurred_at' => now()->subDays(($index * 3) + $n + 1),
                    ]
                );

                if (in_array($violation['status'], ['consequence_applied', 'resolved'], true)) {
                    $quiz = \App\Models\Quiz::ensureForViolation($violationRecord);

                    $existingAttempt = \App\Models\QuizAttempt::where('quiz_id', $quiz->id)
                        ->where('user_id', $student->id)
                        ->first();

                    if (!$existingAttempt) {
                        \App\Models\QuizAttempt::create([
                            'quiz_id' => $quiz->id,
                            'user_id' => $student->id,
                            'answers' => [
                                'reasoning' => 'I understand the rule and will follow campus guidelines moving forward.',
                            ],
                            'score' => 85,
                            'total_questions' => 1,
                            'passed' => true,
                            'admin_approved' => $violation['status'] === 'resolved' ? true : null,
                            'admin_note' => $violation['status'] === 'resolved' ? 'Reasoning accepted and case resolved.' : 'Awaiting admin review for appeal.',
                            'reviewed_at' => $violation['status'] === 'resolved' ? now()->subDay() : null,
                        ]);
                    }
                }
            }
        }
    }
}