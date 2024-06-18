<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Attendance;
use App\Models\AttendanceOption;
use App\Models\Lesson;
use App\Models\Student;

class AttendanceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Attendance::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $lesson = Lesson::all()->random();
        $student = Student::where('group_id', $lesson->group_id)->get()->random();
        return [
            'attendance_option_id' => AttendanceOption::where('type', 'attendance')->get()->random()->id,
            'student_id' => $student->id,
            'lesson_id' => $lesson->id,
        ];
    }
}
