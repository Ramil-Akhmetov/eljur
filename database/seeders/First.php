<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\AttendanceOption;
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

//        TeacherSubject::factory(10)->create();

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

//        Lesson::factory(100)->create();
//        Attendance::factory(300)->create();
//
//        GradeMonth::factory(100)->create();
//        GradeSemester::factory(100)->create();

//        $start_of_month = Date('2023-09-01');
//        $end_of_month = Date('2024-06-t');
        $start_of_month = Date('2024-06-01');
        $end_of_month = Date('2024-06-t');
        foreach ($groups as $group) {
            $students = Student::where('group_id', $group->id)->get();

            $subjects = TeacherGroupSubject::where('group_id', $group->id)->get()->map(function ($item) {
                return $item->subject;
            })->unique('id');

            foreach ($subjects as $subject) {
                $start = $start_of_month;
                while ($start < $end_of_month) {
                    $teacher_id = TeacherGroupSubject::
                    where('group_id', $group->id)
                        ->whereHas('teacherSubject', function ($query) use ($subject) {
                            $query->where('subject_id', $subject->id);
                        })
                        ->first()
                        ->teacherSubject->teacher_id;

                    for ($j = 0; $j < 2; $j++) {
                        $lesson = Lesson::factory()->create([
                            'subject_id' => $subject->id,
                            'classroom_id' => Classroom::all()->random()->id,
                            'teacher_id' => $teacher_id,
                            'group_id' => $group->id,
                            'date' => $start,
                        ]);
                        foreach ($students as $student) {
                            $a = Attendance::create([
                                'lesson_id' => $lesson->id,
                                'student_id' => $student->id,
                                'attendance_option_id' => AttendanceOption::where('type', 'attendance')->get()->random()->id,
                            ]);
                        }
                    }
                    $start = Date('Y-m-d', strtotime($start . ' + 3 days'));
                }
                foreach ($students as $student) {
                    $g = GradeMonth::create([
                        'date' => Date('2024-06-t'),
                        'student_id' => $student->id,
                        'subject_id' => $subject->id,
                        'attendance_option_id' => AttendanceOption::where('type', 'grade')->get()->random()->id,
                    ]);
                }
                foreach ($subject->semesters as $semester) {
                    foreach ($students as $student) {
                        $g = GradeSemester::create([
                            'attendance_option_id' => AttendanceOption::where('type', 'grade')->get()->random()->id,
                            'student_id' => $student->id,
                            'subject_id' => $subject->id,
                            'semester_id' => $semester->id,
                        ]);
                    }
                }
            }
        }

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
