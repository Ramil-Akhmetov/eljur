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
        return [
            'mark' => $this->faker->word(),
            'attendance_option_id' => AttendanceOption::all()->random()->id,
            'student_id' => Student::factory(),
            'lesson_id' => Lesson::factory(),
        ];
    }
}
