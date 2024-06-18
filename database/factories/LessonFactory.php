<?php

namespace Database\Factories;

use App\Models\TeacherGroupSubject;
use App\Models\Topic;
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
//        $teacherGroupSubject = TeacherGroupSubject::all()->random();
//
//        $group = $teacherGroupSubject->group_id;
//        $subject = $teacherGroupSubject->teacherSubject->subject_id;
//        $teacher = $teacherGroupSubject->teacherSubject->teacher_id;

        $group = Group::all()->random()->id;
        $subject = Subject::all()->random()->id;
        $teacher = Teacher::all()->random()->id;

        return [
            'date' => $this->faker->dateTimeBetween('-2months', 'now')->format('Y-m-d'),
            'topic' => $this->faker->sentence,
            'teacher_id' => $teacher,
            'group_id' => $group,
            'subject_id' => $subject,
            'classroom_id' => Classroom::all()->random()->id,
            'lesson_type_id' => LessonType::all()->random()->id,
        ];
    }
}
