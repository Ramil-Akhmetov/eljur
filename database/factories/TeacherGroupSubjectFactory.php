<?php

namespace Database\Factories;

use App\Models\TeacherSubject;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Group;
use App\Models\TeacherGroupSubject;

class TeacherGroupSubjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TeacherGroupSubject::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        while (true) {
            $teacher_subject = TeacherSubject::all()->random();
            $groups = Group::where('group_status_id', 1)->where('specialty_id', $teacher_subject->subject->specialty_id)->get();


            if ($groups->count() > 0) {
                $group = $groups->random();
                if (TeacherGroupSubject::where('teacher_subject_id', $teacher_subject->id)->where('group_id', $group->id)->exists()) {
                    continue;
                } else {
                    break;
                }
            }
        }

        return [
            'teacher_subject_id' => $teacher_subject->id,
            'group_id' => $group->id,
        ];
    }
}
