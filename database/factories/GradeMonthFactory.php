<?php

namespace Database\Factories;

use App\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\AttendanceOption;
use App\Models\GradeMonth;
use App\Models\Student;
use App\Models\Subject;

class GradeMonthFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GradeMonth::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $lesson = Lesson::all()->random();
        $student = $lesson->group->students->random();
        $subject = $lesson->subject;
        $date = $lesson->date->format('Y-m-t');
        return [
            'attendance_option_id' => AttendanceOption::where('type', 'grade')->get()->random()->id,
            'student_id' => $student,
            'subject_id' => $subject,
            'date' => $date,
        ];
    }
}
