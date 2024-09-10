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
use Database\Factories\ClassroomFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AutoComplete extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Classroom::factory(15)->create();
        $specialties = Specialty::factory(5)->create();

        foreach ($specialties as $specialty) {
            $subjects = Subject::factory(10)->create([
                'specialty_id' => $specialty->id,
            ]);
            foreach ($subjects as $subject) {
                $semester_array = Semester::all()->random(\rand(1, 2));
                $subject->semesters()->sync($semester_array);
            }
        }

        $groups = Group::factory(15)->create();

        foreach ($groups as $group) {
            for ($i = 0; $i < 3; $i++) {
                $studentUser = User::factory()->create([
                    'role_id' => 3,
                ]);
                $student = Student::factory()->create([
                    'user_id' => $studentUser->id,
                    'group_id' => $group->id,
                ]);
            }
        }

        for ($k = 0; $k < 10; $k++) {
            $teacherUsers = User::factory()->create([
                'role_id' => 2,
            ]);
            $teacher = $teacherUsers->teacher()->create();

            for ($i = 0; $i < \rand(1, 3); $i++) {
                $semester = Semester::all()->random();
                $subject = Subject::whereHas('semesters', function ($q) use ($semester) {
                    $q->where('id', $semester->id);
                })->get()->random();
                $ts = TeacherSubject::factory()->create([
                    'teacher_id' => $teacher->id,
                    'subject_id' => $subject->id,
                ]);


                //groups where semester = semester
                $gs = Group::where('semester_id', $semester->id)->get();

                foreach ($gs as $g) {
                    $tgs = TeacherGroupSubject::factory()->create([
                        'teacher_subject_id' => $ts->id,
                        'group_id' => $g->id,
                    ]);
                }
            }
        }

//        $teacherUser1 = User::factory()->create([
//            'role_id' => 2,
//        ]);
//        $teacher1 = $teacherUser1->teacher()->create();
//        $ts1 = $teacher1->teacherSubjects()->create([
//            'subject_id' => $subject_1->id,
//        ]);
//        $tgs1 = TeacherGroupSubject::factory()->create([
//            'teacher_subject_id' => $ts1->id,
//            'group_id' => $group_1->id,
//        ]);
//        $ts2 = $teacher1->teacherSubjects()->create([
//            'subject_id' => $subject_2->id,
//        ]);
//        $tgs2 = TeacherGroupSubject::factory()->create([
//            'teacher_subject_id' => $ts2->id,
//            'group_id' => $group_2->id,
//        ]);
//
//
//        $teacherUser2 = User::factory()->create([
//            'role_id' => 2,
//        ]);
//        $teacher2 = $teacherUser2->teacher()->create();
//        $ts1 = $teacher2->teacherSubjects()->create([
//            'subject_id' => $subject_3->id,
//        ]);
//        $tgs1 = TeacherGroupSubject::factory()->create([
//            'teacher_subject_id' => $ts1->id,
//            'group_id' => $group_3->id,
//        ]);
//        $ts2 = $teacher2->teacherSubjects()->create([
//            'subject_id' => $subject_4->id,
//        ]);
//        $tgs2 = TeacherGroupSubject::factory()->create([
//            'teacher_subject_id' => $ts2->id,
//            'group_id' => $group_4->id,
//        ]);


        //create lessons for all groups and each subject
        $start_of_month = Date('2024-05-01');
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
                    $start = Date('Y-m-d', strtotime($start . ' + 7 days'));
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
