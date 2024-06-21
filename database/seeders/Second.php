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

class Second extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Classroom::factory(10)->create();

        for ($i = 0; $i < 3; $i++) {
            $specialty = Specialty::factory()->create([
                'name' => 'Специальность ' . $i,
                'code' => 'код-' . $i,
            ]);

            for ($j = 0; $j < 10; $j++) {
                $subject = Subject::factory()->create([
                    'name' => 'Дисциплина ' . $j,
                    'specialty_id' => $specialty->id,
                ]);
                $semester_array = Semester::all()->random(\rand(1, 2));
                $subject->semesters()->sync($semester_array);
            }
        }

        for ($l = 0; $l < 8; $l++) {
            $group = Group::factory()->create([
                'code' => 'Group ' . $l,
            ]);
            for ($m = 0; $m < 5; $m++) {
                $studentUser = User::factory()->create([
                    'surname' => 'Студент ' . $m,
                    'name' => 'имя',
                    'patronymic' => 'отчество',
                    'role_id' => 3,
                ]);
                $student = Student::factory()->create([
                    'user_id' => $studentUser->id,
                    'group_id' => $group->id,
                ]);
            }
        }

        for ($k = 0; $k < 5; $k++) {
            $teacherUsers = User::factory()->create([
                'surname' => 'Преподаватель ' . $k,
                'name' => 'имя',
                'patronymic' => 'отчество',
                'role_id' => 2,
            ]);
            $teacher = $teacherUsers->teacher()->create();

            for ($i = 0; $i < \rand(1, 3); $i++) {
                $subject = Subject::all()->random();
                $ts = TeacherSubject::factory()->create([
                    'teacher_id' => $teacher->id,
                    'subject_id' => $subject->id,
                ]);
                $group = Group::all()->random();

                $tgs = TeacherGroupSubject::factory()->create([
                    'teacher_subject_id' => $ts->id,
                    'group_id' => $group,
                ]);
            }
        }


        for ($n = 0; $n < 100; $n++) {
            $lesson = Lesson::factory()->create();

            for ($o = 0; $o < 5; $o++) {
                $attendace = Attendance::factory()->create([
                    'lesson_id' => $lesson->id,
                ]);
            }
        }

        GradeMonth::factory(100)->create();
        GradeSemester::factory(100)->create();
    }
}
