<?php

namespace Database\Factories;

use App\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\AttendanceOption;
use App\Models\GradeSemester;
use App\Models\Semester;
use App\Models\Student;
use App\Models\Subject;

class GradeSemesterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GradeSemester::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $attendanceOption = AttendanceOption::where('type', 'grade')->get()->random();
        $lesson = Lesson::all()->random();
        $student = $lesson->group->students->random();
        $subject = $lesson->subject;
        $semester = $student->group->getCurrentSemesterAttribute();

        return [
            'attendance_option_id' => $attendanceOption->id,
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'semester_id' => $semester->id,
        ];
    }
}
