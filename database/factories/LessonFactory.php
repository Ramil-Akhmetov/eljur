<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Classroom;
use App\Models\Group;
use App\Models\Lesson;
use App\Models\LessonType;
use App\Models\Subject;
use App\Models\Teacher;

class LessonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Lesson::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'topic' => $this->faker->word(),
            'timestamp' => $this->faker->dateTime(),
            'teacher_id' => Teacher::factory(),
            'group_id' => Group::factory(),
            'subject_id' => Subject::factory(),
            'classroom_id' => Classroom::factory(),
            'lesson_type_id' => LessonType::factory(),
        ];
    }
}
