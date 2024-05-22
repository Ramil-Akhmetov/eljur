<?php

namespace Database\Seeders;

use App\Models\LessonType;
use App\Models\Role;
use App\Models\StudentStatus;
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
        // User::factory(10)->create();

        Role::factory()->create([
            'name' => 'Студент',
        ]);
        Role::factory()->create([
            'name' => 'Администратор',
        ]);
        Role::factory()->create([
            'name' => 'Преподаватель',
        ]);

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@email.com',
        ]);

        StudentStatus::factory()->create(['name' => 'Отчислен']);
        StudentStatus::factory()->create(['name' => 'Активен']);
        StudentStatus::factory()->create(['name' => 'Переведен']);

        LessonType::factory()->create(['short_name' => 'КР', 'name' => 'Контрольная работа']);
        LessonType::factory()->create(['short_name' => 'СР', 'name' => 'Самостоятельная работа']);
        LessonType::factory()->create(['short_name' => 'ПР', 'name' => 'Практическая работа']);
        LessonType::factory()->create(['short_name' => 'ЛР', 'name' => 'Лабораторная работа']);
    }
}
