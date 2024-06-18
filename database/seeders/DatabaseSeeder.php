<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\AttendanceOption;
use App\Models\Classroom;
use App\Models\GradeMonth;
use App\Models\GradeSemester;
use App\Models\Group;
use App\Models\GroupStatus;
use App\Models\Lesson;
use App\Models\LessonType;
use App\Models\Role;
use App\Models\Semester;
use App\Models\Specialty;
use App\Models\Student;
use App\Models\StudentStatus;
use App\Models\Subject;
use App\Models\TeacherGroupSubject;
use App\Models\TeacherSubject;
use App\Models\Topic;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        for ($i = 0; $i < 8; $i++) {
            Semester::factory()->create(['number' => $i + 1]);
        }

        Role::factory()->create(['name' => 'Администратор']);
        Role::factory()->create(['name' => 'Преподаватель']);
        Role::factory()->create(['name' => 'Студент']);

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@email.com',
            'role_id' => 1,
        ]);


        StudentStatus::factory()->create(['name' => 'Активен']);
        StudentStatus::factory()->create(['name' => 'Отчислен']);
        StudentStatus::factory()->create(['name' => 'Переведен']);
        StudentStatus::factory()->create(['name' => 'Выпустился']);

        LessonType::factory()->create(['short_name' => 'КР', 'name' => 'Контрольная работа']);
        LessonType::factory()->create(['short_name' => 'СР', 'name' => 'Самостоятельная работа']);
        LessonType::factory()->create(['short_name' => 'ПР', 'name' => 'Практическая работа']);
        LessonType::factory()->create(['short_name' => 'ЛР', 'name' => 'Лабораторная работа']);

        AttendanceOption::factory()->create(['short_name' => '-', 'name' => '-', 'type' => 'attendance']);
        AttendanceOption::factory()->create(['short_name' => '2', 'name' => '2', 'type' => 'attendance']);
        AttendanceOption::factory()->create(['short_name' => '3', 'name' => '3', 'type' => 'attendance']);
        AttendanceOption::factory()->create(['short_name' => '4', 'name' => '4', 'type' => 'attendance']);
        AttendanceOption::factory()->create(['short_name' => '5', 'name' => '5', 'type' => 'attendance']);
        AttendanceOption::factory()->create(['short_name' => 'н', 'name' => 'Неуважительная причина', 'type' => 'attendance']);
        AttendanceOption::factory()->create(['short_name' => 'у', 'name' => 'Уважительная причина', 'type' => 'attendance']);


        AttendanceOption::factory()->create(['short_name' => '-', 'name' => '-', 'type' => 'grade']);
        AttendanceOption::factory()->create(['short_name' => '2', 'name' => '2', 'type' => 'grade']);
        AttendanceOption::factory()->create(['short_name' => '3', 'name' => '3', 'type' => 'grade']);
        AttendanceOption::factory()->create(['short_name' => '4', 'name' => '4', 'type' => 'grade']);
        AttendanceOption::factory()->create(['short_name' => '5', 'name' => '5', 'type' => 'grade']);
        AttendanceOption::factory()->create(['short_name' => 'н/а', 'name' => 'Не аттестован', 'type' => 'grade']);
        AttendanceOption::factory()->create(['short_name' => 'н/аб', 'name' => '???', 'type' => 'grade']);
        AttendanceOption::factory()->create(['short_name' => 'Зач.', 'name' => 'Зачет', 'type' => 'grade']);


        GroupStatus::factory()->create(['name' => 'Активна']);
        GroupStatus::factory()->create(['name' => 'Не активна']);
        GroupStatus::factory()->create(['name' => 'Выпустилась']);

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

//        for ($l = 0; $l < 100; $l++) {
//            $ts = TeacherSubject::factory(1)->create();
//            $tgs = TeacherGroupSubject::factory(1)->create([
//                'teacher_subject_id' => $ts->first()->id,
//            ]);
//        }

        TeacherSubject::factory(100)->create();
        TeacherGroupSubject::factory(100)->create();
//        for ($m = 0; $m < 100; $m++) {
//            $l = Lesson::factory()->create();
//            Attendance::factory(5)->create([
//                'lesson_id' => $l->id,
//            ]);
//        }

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
