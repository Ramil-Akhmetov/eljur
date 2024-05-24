<?php

namespace Database\Seeders;

use App\Models\AttendanceOption;
use App\Models\Classroom;
use App\Models\Group;
use App\Models\GroupStatus;
use App\Models\LessonType;
use App\Models\Role;
use App\Models\Specialty;
use App\Models\Student;
use App\Models\StudentStatus;
use App\Models\Subject;
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

        AttendanceOption::factory()->create(['short_name' => 'Н', 'name' => 'Неуважительная причина']);
        AttendanceOption::factory()->create(['short_name' => 'У', 'name' => 'Уважительная причина']);

        GroupStatus::factory()->create(['name' => 'Не активна']);
        GroupStatus::factory()->create(['name' => 'Активен']);
        GroupStatus::factory()->create(['name' => 'Выпустилась']);

        // Seeding
        Classroom::factory(20)->create();
        Specialty::factory(10)->create();

        Subject::factory(50)->create();

        $teacherUsers = User::factory(5)->create([
            'role_id' => 2,
        ]);
        $teacherUsers->each(function ($user) {
            $teacher = $user->teacher()->create();
            $teacher->subjects()->sync(Subject::all()->random(10));
        });

        $groups = Group::factory(10)->create();
        $groups->each(function ($group) {
            $studentUsers = User::factory(20)->create([
                'role_id' => 3,
            ]);
            $studentUsers->each(function ($user) use ($group) {
                $student = Student::factory()->create(['user_id' => $user->id, 'group_id' => $group->id]);
            });
        });
    }
}
