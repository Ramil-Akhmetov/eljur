<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\AttendanceOption;
use App\Models\Classroom;
use App\Models\GradeMonth;
use App\Models\GradeSemester;
use App\Models\Group;
use App\Models\Lesson;
use App\Models\Specialty;
use App\Models\Student;
use App\Models\Subject;
use App\Models\TeacherGroupSubject;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Complete extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Classroom::factory(5)->create();

        $specialty_1 = Specialty::factory()->create([
            'name' => 'Программирование в компьютерных системах',
            'code' => '09.02.03',
        ]);
        $specialty_2 = Specialty::factory()->create([
            'name' => 'Монтаж, наладка и эксплуатация',
            'code' => '08.02.09',
        ]);


        $spec_1_subject_1 = Subject::factory()->create([
            'name' => 'Основы программирования',
            'specialty_id' => $specialty_1->id,
        ]);
        $spec_1_subject_1->semesters()->sync([1, 2]);
        $spec_1_subject_2 = Subject::factory()->create([
            'name' => 'Веб-программирование',
            'specialty_id' => $specialty_1->id,
        ]);
        $spec_1_subject_2->semesters()->sync([3, 4]);
        $spec_1_subject_3 = Subject::factory()->create([
            'name' => 'Создание мобильных приложений',
            'specialty_id' => $specialty_1->id,
        ]);
        $spec_1_subject_3->semesters()->sync([4]);
        $spec_1_subject_4 = Subject::factory()->create([
            'name' => 'Базы данных',
            'specialty_id' => $specialty_1->id,
        ]);
        $spec_1_subject_4->semesters()->sync([2]);


        $spec_2_subject_1 = Subject::factory()->create([
            'name' => 'Основы монтажа',
            'specialty_id' => $specialty_2->id,
        ]);
        $spec_2_subject_1->semesters()->sync([1, 2]);
        $spec_2_subject_2 = Subject::factory()->create([
            'name' => 'Электроника',
            'specialty_id' => $specialty_2->id,
        ]);
        $spec_2_subject_2->semesters()->sync([3, 4]);
        $spec_2_subject_3 = Subject::factory()->create([
            'name' => 'Электротехника',
            'specialty_id' => $specialty_2->id,
        ]);
        $spec_2_subject_3->semesters()->sync([4]);
        $spec_2_subject_4 = Subject::factory()->create([
            'name' => 'Автоматика',
            'specialty_id' => $specialty_2->id,
        ]);
        $spec_2_subject_4->semesters()->sync([2]);


        $spec_1_subject_5 = Subject::factory()->create([
            'name' => 'Высшая математика',
            'specialty_id' => $specialty_1->id,
        ]);
        $spec_1_subject_5->semesters()->sync([2, 3, 4]);
        $spec_2_subject_5 = Subject::factory()->create([
            'name' => 'Высшая математика',
            'specialty_id' => $specialty_2->id,
        ]);
        $spec_2_subject_5->semesters()->sync([2, 3, 4]);


        $group_1 = Group::factory()->create([
            'code' => 'c01',
            'specialty_id' => $specialty_1->id,
            'start_date' => '2023-09-01',
            'semester_id' => 2,
            'group_status_id' => 1
        ]);
        $group_2 = Group::factory()->create([
            'code' => 'c02',
            'specialty_id' => $specialty_1->id,
            'start_date' => '2022-09-01',
            'semester_id' => 4,
            'group_status_id' => 1
        ]);

        $group_3 = Group::factory()->create([
            'code' => 'c03',
            'specialty_id' => $specialty_2->id,
            'start_date' => '2021-09-01',
            'semester_id' => 2,
            'group_status_id' => 1
        ]);
        $group_4 = Group::factory()->create([
            'code' => 'c04',
            'specialty_id' => $specialty_2->id,
            'start_date' => '2020-09-01',
            'semester_id' => 4,
            'group_status_id' => 1
        ]);

        $group_5 = Group::factory()->create([
            'code' => 'c25',
            'specialty_id' => $specialty_1->id,
            'start_date' => '2019-09-01',
            'semester_id' => 8,
            'group_status_id' => 3
        ]);

        $groups = [$group_1, $group_2, $group_3, $group_4, $group_5];

        foreach ($groups as $group) {
            for ($i = 0; $i < 4; $i++) {
                $studentUser = User::factory()->create([
                    'role_id' => 3,
                ]);
                $student = Student::factory()->create([
                    'user_id' => $studentUser->id,
                    'group_id' => $group->id,
                ]);
            }
        }

        $teacherUser1 = User::factory()->create([
            'role_id' => 2,
        ]);
        $teacher1 = $teacherUser1->teacher()->create();
        $ts1 = $teacher1->teacherSubjects()->create([
            'subject_id' => $spec_1_subject_1->id,
        ]);
        $tgs1 = TeacherGroupSubject::factory()->create([
            'teacher_subject_id' => $ts1->id,
            'group_id' => $group_1->id,
        ]);
        $ts2 = $teacher1->teacherSubjects()->create([
            'subject_id' => $spec_1_subject_2->id,
        ]);
        $tgs2 = TeacherGroupSubject::factory()->create([
            'teacher_subject_id' => $ts2->id,
            'group_id' => $group_2->id,
        ]);
        $ts3 = $teacher1->teacherSubjects()->create([
            'subject_id' => $spec_1_subject_3->id,
        ]);
        $tgs3 = TeacherGroupSubject::factory()->create([
            'teacher_subject_id' => $ts3->id,
            'group_id' => $group_2->id,
        ]);
        $ts4 = $teacher1->teacherSubjects()->create([
            'subject_id' => $spec_1_subject_4->id,
        ]);
        $tgs4 = TeacherGroupSubject::factory()->create([
            'teacher_subject_id' => $ts4->id,
            'group_id' => $group_1->id,
        ]);




        $teacherUser2 = User::factory()->create([
            'role_id' => 2,
        ]);
        $teacher2 = $teacherUser2->teacher()->create();
        $ts1 = $teacher2->teacherSubjects()->create([
            'subject_id' => $spec_2_subject_1->id,
        ]);
        $tgs1 = TeacherGroupSubject::factory()->create([
            'teacher_subject_id' => $ts1->id,
            'group_id' => $group_3->id,
        ]);
        $ts2 = $teacher2->teacherSubjects()->create([
            'subject_id' => $spec_2_subject_2->id,
        ]);
        $tgs2 = TeacherGroupSubject::factory()->create([
            'teacher_subject_id' => $ts2->id,
            'group_id' => $group_4->id,
        ]);
        $ts3 = $teacher2->teacherSubjects()->create([
            'subject_id' => $spec_2_subject_3->id,
        ]);
        $tgs3 = TeacherGroupSubject::factory()->create([
            'teacher_subject_id' => $ts3->id,
            'group_id' => $group_4->id,
        ]);
        $ts4 = $teacher2->teacherSubjects()->create([
            'subject_id' => $spec_2_subject_4->id,
        ]);
        $tgs4 = TeacherGroupSubject::factory()->create([
            'teacher_subject_id' => $ts4->id,
            'group_id' => $group_3->id,
        ]);




        $ts5 = $teacher1->teacherSubjects()->create([
            'subject_id' => $spec_2_subject_5->id,
        ]);
        $tgs5 = TeacherGroupSubject::factory()->create([
            'teacher_subject_id' => $ts5->id,
            'group_id' => $group_3->id,
        ]);
        $tgs5 = TeacherGroupSubject::factory()->create([
            'teacher_subject_id' => $ts5->id,
            'group_id' => $group_4->id,
        ]);

        $ts5 = $teacher2->teacherSubjects()->create([
            'subject_id' => $spec_1_subject_5->id,
        ]);
        $tgs5 = TeacherGroupSubject::factory()->create([
            'teacher_subject_id' => $ts5->id,
            'group_id' => $group_1->id,
        ]);
        $tgs5 = TeacherGroupSubject::factory()->create([
            'teacher_subject_id' => $ts5->id,
            'group_id' => $group_2->id,
        ]);


        //create lessons for all groups and each subject
        $start_of_month = Date('2024-09-01');
        $end_of_month = Date('2024-12-t');

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
                    $start = Date('Y-m-d', strtotime($start . ' + 4 days'));
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
    }
}
