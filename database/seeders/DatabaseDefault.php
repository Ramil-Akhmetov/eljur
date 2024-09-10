<?php

namespace Database\Seeders;

use App\Models\AttendanceOption;
use App\Models\GroupStatus;
use App\Models\LessonType;
use App\Models\Role;
use App\Models\Semester;
use App\Models\StudentStatus;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseDefault extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 8; $i++) {
            Semester::factory()->create(['number' => $i + 1]);
        }

        Role::factory()->create(['name' => 'Администратор']);
        Role::factory()->create(['name' => 'Преподаватель']);
        Role::factory()->create(['name' => 'Студент']);

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

        User::factory()->create([
            'surname' => 'admin',
            'name' => 'admin',
            'patronymic' => 'admin',
            'email' => 'admin@email.com',
            'role_id' => 1,
        ]);
    }
}
