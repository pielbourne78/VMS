<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class NormalizeStudentCourseData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'normalize:student-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Normalize old free-text course and section values for student accounts to the new standardized format.';

    /**
     * Map of old course values to new standardized course names.
     */
    protected array $courseMap = [
        'BSIT' => 'BS Information Technology',
        'BSBA' => 'BS Business Administration - Human Resources Dev\'t Mgt.',
        'BSA' => 'BS Accountancy',
        'BSEntrep' => 'BS Entrepreneurship',
    ];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $students = User::where('is_admin', false)->orWhereNull('is_admin')->get();

        $updatedCourses = 0;
        $updatedSections = 0;

        foreach ($students as $student) {
            $dirty = false;

            // Normalize course
            if ($student->course && array_key_exists($student->course, $this->courseMap)) {
                $student->course = $this->courseMap[$student->course];
                $dirty = true;
                $updatedCourses++;
            }

            // Normalize section: strip any non-digit prefix (e.g. "IT-301" -> "301")
            if ($student->section && preg_match('/(\d{3})$/', $student->section, $matches)) {
                if ($student->section !== $matches[1]) {
                    $student->section = $matches[1];
                    $dirty = true;
                    $updatedSections++;
                }
            }

            if ($dirty) {
                $student->save();
            }
        }

        $this->info("Normalized {$updatedCourses} course value(s) and {$updatedSections} section value(s).");

        return self::SUCCESS;
    }
}