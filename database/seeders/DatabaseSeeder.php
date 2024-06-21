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
        $this->call([
            DatabaseDefault::class,
//            First::class,
//            Second::class,
            Complete::class,
//            AutoComplete::class,
        ]);

    }
}
