<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\GradeMonth;
use App\Models\GradeSemester;
use App\Models\Group;
use App\Models\Lesson;
use App\Models\Semester;
use App\Models\Specialty;
use App\Models\Student;
use App\Models\Subject;
use App\Models\TeacherGroupSubject;
use App\Models\TeacherSubject;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class First extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seeding
        Classroom::factory(10)->create();

        for ($k = 0; $k < 3; $k++) {
            $s = Specialty::factory()->create();

            for ($j = 0; $j < 10; $j++) {
                $subject = Subject::factory()->create([
                    'specialty_id' => $s->id,
                ]);
                $semester_array = Semester::all()->random(\rand(1, 2));
                $subject->semesters()->sync($semester_array);
            }
        }

        $teacherUsers = User::factory(5)->create([
            'role_id' => 2,
        ]);
        $teacherUsers->each(function ($user) {
            $teacher = $user->teacher()->create();
            $teacher->subjects()->sync(Subject::all()->random(3));
        });

        $groups = Group::factory(6)->create();
        $groups->each(function ($group) {
            $studentUsers = User::factory(5)->create([
                'role_id' => 3,
            ]);
            $studentUsers->each(function ($user) use ($group) {
                $student = Student::factory()->create(['user_id' => $user->id, 'group_id' => $group->id]);
            });
        });

        TeacherSubject::factory(10)->create();

        $teacherUsers->each(function ($user) {
            $teacher = $user->teacher;

            for ($i = 0; $i < 3; $i++) {
                $subject = Subject::all()->random();
                $ts = TeacherSubject::factory()->create([
                    'teacher_id' => $teacher->id,
                    'subject_id' => $subject->id,
                ]);

                $tgs = TeacherGroupSubject::factory()->create([
                    'teacher_subject_id' => $ts->id,
                    'group_id' => Group::all()->random()->id,
                ]);
            }
        });

        Lesson::factory(100)->create();
        Attendance::factory(300)->create();

        GradeMonth::factory(100)->create();
        GradeSemester::factory(100)->create();

        User::factory()->create([
            'surname' => 'Ахметов',
            'name' => 'Рамиль',
            'patronymic' => 'Русланович',
            'email' => 'ramil@gmail.com',
            'phone' => '88005553535',
            'sex' => 'Мужчина',
            'birthdate' => fake()->date('2004-04-19'),
            'role_id' => null,
        ]);
    }
}
