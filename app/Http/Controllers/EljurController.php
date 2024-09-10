<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceOption;
use App\Models\GradeMonth;
use App\Models\GradeSemester;
use App\Models\Semester;
use App\Models\Student;
use App\Models\TeacherGroupSubject;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Subject;
use App\Models\Lesson;
use Illuminate\Support\Facades\DB;
use Termwind\Components\Dd;

class EljurController extends Controller
{
    public function template()
    {
        return view('eljur_template');
    }

    public function showCreateEljur(Request $request)
    {
        $user = backpack_user();
        if ($user->role->name !== 'Администратор' && $user->role->name !== 'Преподаватель') {
            return view('error', ['error_number' => 403, 'title' => 'У вас нет доступа к этой странице']);
        }
        $group_id = $request->query('group_id') ?? null;

        $subject_id = $request->query('subject_id') ?? null;

        $groups = null;

        if ($user->role->name === 'Администратор') {
            $groups = Group::where('group_status_id', 1)->get();
        } else if ($user->role->name === 'Преподаватель') {
            $teacherId = $user->teacher->id;
            $groups = TeacherGroupSubject::whereHas('teacherSubject', function ($query) use ($teacherId, $subject_id) {
                $query->where('teacher_id', $teacherId);
            })
                ->whereHas('group', function ($query) {
                $query->where('group_status_id', 1);
            })
                ->get()->map(function ($item) {
                return $item->group;
            })->unique('id');
        }

        $subjects = null;
        $students = null;
        $lessons = null;

        $start_date = $request->input('start_date');
        if (!$start_date) {
            $start_date = date('Y-m-01');
        }
        $end_date = $request->input('end_date');
        if (!$end_date) {
            $end_date = date('Y-m-t');
        }

        if ($group_id) {
            if ($user->role->name === 'Администратор') {
                $subjects = TeacherGroupSubject::where('group_id', $group_id)->get()->map(function ($item) {
                    return $item->subject;
                })->unique('id');

            } else if ($user->role->name === 'Преподаватель') {
                $subjects = $user->teacher->subjectsForGroup($group_id);
            }
            $students = Student::query()
                ->where('group_id', $group_id)
                ->join('users', 'students.user_id', '=', 'users.id')
                ->orderBy('users.surname', 'ASC')
                ->orderBy('users.name', 'ASC')
                ->orderBy('users.patronymic', 'ASC')
                ->select('students.*', 'users.surname', 'users.name', 'users.patronymic')
                ->get();

            $lessons = Lesson::where('group_id', $group_id)
                ->where('subject_id', $subject_id)
                ->where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->orderBy('date', 'ASC')->get();
        }


        return view('eljur', [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'groups' => $groups,
            'group_id' => $group_id,
            'subjects' => $subjects,
            'subject_id' => $subject_id,
            'students' => $students,
            'lessons' => $lessons
        ]);
    }

    public function showEljurStudent(Request $request)
    {
        $user = backpack_user();
        if ($user->role->name !== 'Студент') {
            return view('error', ['error_number' => 403, 'title' => 'У вас нет доступа к этой странице']);
        }

        $group = $user->student->group;

        $selected_subject_id = $request->query('subject_id');

        $start_date = $request->query('start_date');
        if (!$start_date) {
            $start_date = date('Y-m-01');
        }
        $end_date = $request->query('end_date');
        if (!$end_date) {
            $end_date = date('Y-m-t');
        }

        $lessons = Lesson::where('group_id', $group->id)
            ->where('subject_id', $selected_subject_id)
            ->where('date', '>=', $start_date)
            ->where('date', '<=', $end_date)
            ->orderBy('date', 'ASC')->get();

        $subjects = TeacherGroupSubject::query()
            ->where('group_id', $group->id)
            ->get()
            ->map(function ($item) {
                return $item->subject;
            })->unique('id');

        return view('eljur_student', [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'subject_id' => $selected_subject_id,
            'subjects' => $subjects,
            'student' => \backpack_user()->student,
            'lessons' => $lessons
        ]);
    }

    public function saveEljur(Request $request)
    {
        $input = $request->all();

        foreach ($input['attendances'] as $i => $attendance_option) {
            $str = \explode('|', $i);
            $student_id = $str[0];
            $lesson_id = $str[1];
            $existed_attendance_id = $str[2];

            $existed_attendance = Attendance::find($existed_attendance_id);

            if ($existed_attendance) {
                $existed_attendance->attendance_option_id = $attendance_option;
                $existed_attendance->save();
            } else {
                $attendance = Attendance::create([
                    'student_id' => $student_id,
                    'lesson_id' => $lesson_id,
                    'attendance_option_id' => $attendance_option
                ]);
            }
        }

        foreach ($input['grades'] as $i => $attendance_option) {
            $str = \explode('|', $i);
            $student_id = $str[0];
            $lesson_id = $str[1];
            $existed_grade_month_id = $str[2];
            $lesson = Lesson::find($lesson_id);

            $existed_grade_month = GradeMonth::find($existed_grade_month_id);


            if ($existed_grade_month) {
                $existed_grade_month->attendance_option_id = $attendance_option;
                $existed_grade_month->save();
            } else {
                $grade_month = GradeMonth::create([
                    'student_id' => $student_id,
                    'date' => $lesson->date->format('Y-m-t'),
                    'attendance_option_id' => $attendance_option,
                    'subject_id' => $lesson->subject_id,
                ]);
            }
        }

        \Alert::add('success', 'Данные успешно сохранены');

        return redirect()->route('eljur', [
            'group_id' => $input['group_id'],
            'subject_id' => $input['subject_id'],
            'start_date' => $input['start_date'] ?? null,
            'end_date' => $input['end_date'] ?? null
        ]);
    }

    public function eljurAdd(Request $request)
    {
        $input = $request->validate([
            'date' => 'required|date',
            'topic' => 'required|string',
            'count' => 'required|integer',
            'group_id' => 'required|integer',
            'subject_id' => 'required|integer',
            'classroom_id' => 'required|integer',
            'lesson_type_id' => 'required|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        if (!\backpack_user()->teacher) {
            return view('error', ['error_number' => 403, 'title' => 'У вас нет доступа к этой странице']);
        }

        DB::transaction(function () use ($input) {
            $subject = Subject::find($input['subject_id']);
            $group = Group::find($input['group_id']);

            for ($i = 0; $i < $input['count']; $i++) {
                $lesson = Lesson::create([
                    'date' => $input['date'],
                    'topic' => $input['topic'],
                    'teacher_id' => \backpack_user()->teacher->id,
                    'group_id' => $input['group_id'],
                    'subject_id' => $input['subject_id'],
                    'classroom_id' => $input['classroom_id'],
                    'lesson_type_id' => $input['lesson_type_id']
                ]);
            }
        });

        \Alert::add('success', 'Занятие успешно добавлено');

        return redirect()->route('eljur', [
            'group_id' => $input['group_id'],
            'subject_id' => $input['subject_id'],
            'start_date' => $input['start_date'],
            'end_date' => $input['end_date']
        ]);
    }


    public function showReportGroupMonth(Request $request)
    {
        $user = backpack_user();

        $group_id = $request->query('group_id') ?? null;
        $month = $request->query('month') ?? \date('Y-m');

        if ($user->role->name == 'Студент') {
            $group_id = $user->student->group_id;
        }

        $students = null;
        $subjects = null;
        if ($group_id) {
            $semesterByMonth = Group::find($group_id)->getSemesterByMonth($month);
            if ($semesterByMonth) {
                $subjects = TeacherGroupSubject::where('group_id', $group_id)
                    ->whereHas('teacherSubject', function ($query) use ($semesterByMonth) {
                        $query->whereHas('subject', function ($query) use ($semesterByMonth) {
                            $query->whereHas('semesters', function ($query) use ($semesterByMonth) {
                                $query->where('semester_id', $semesterByMonth->id);
                            });
                        });
                    })
                    ->get()->map(function ($item) {
                        return $item->subject;
                    })->unique('id');

                $students = Student::query()
                    ->where('group_id', $group_id)
                    ->join('users', 'students.user_id', '=', 'users.id')
                    ->orderBy('users.surname', 'ASC')
                    ->orderBy('users.name', 'ASC')
                    ->orderBy('users.patronymic', 'ASC')
                    ->select('students.*', 'users.surname', 'users.name', 'users.patronymic')
                    ->get();
            }
        }


        $groups = Group::where('group_status_id', 1)->get();
        if ($user->role->name === 'Преподаватель') {
            $teacherId = $user->teacher->id;
            $groups = TeacherGroupSubject::whereHas('teacherSubject', function ($query) use ($teacherId) {
                $query->where('teacher_id', $teacherId);
            })
                ->whereHas('group', function ($query) {
                    $query->where('group_status_id', 1);
                })
                ->get()->map(function ($item) {
                    return $item->group;
                })->unique('id');
        }

        return view('report-group-month', [
            'group_id' => $group_id,
            'groups' => $groups,
            'month' => $month,

            'subjects' => $subjects,
            'students' => $students,
            'month_grades' => $students
        ]);
    }

    public function showReportGroupSemester(Request $request)
    {
        $user = backpack_user();

        $group_id = $request->query('group_id') ?? null;
        $semester_id = $request->query('semester_id') ?? null;


        if ($user->role->name == 'Студент') {
            $group_id = $user->student->group_id;
        }

        if ($group_id && !$semester_id) {
            $semester_id = Group::find($group_id)->semester->id;
        }

        $students = null;
        $subjects = null;
        if ($group_id) {
            $subjects = TeacherGroupSubject::where('group_id', $group_id)
                ->whereHas('teacherSubject', function ($query) use ($semester_id) {
                    $query->whereHas('subject', function ($query) use ($semester_id) {
                        $query->whereHas('semesters', function ($query) use ($semester_id) {
                            $query->where('semester_id', $semester_id);
                        });
                    });
                })
                ->get()->map(function ($item) {
                return $item->subject;
            })->unique('id');

            $students = Student::query()
                ->where('group_id', $group_id)
                ->join('users', 'students.user_id', '=', 'users.id')
                ->orderBy('users.surname', 'ASC')
                ->orderBy('users.name', 'ASC')
                ->orderBy('users.patronymic', 'ASC')
                ->select('students.*', 'users.surname', 'users.name', 'users.patronymic')
                ->get();
        }

        $groups = Group::where('group_status_id', 1)->get();
        if ($user->role->name === 'Преподаватель') {
            $teacherId = $user->teacher->id;
            $groups = TeacherGroupSubject::whereHas('teacherSubject', function ($query) use ($teacherId) {
                $query->where('teacher_id', $teacherId);
            })
                ->whereHas('group', function ($query) {
                    $query->where('group_status_id', 1);
                })
                ->get()->map(function ($item) {
                    return $item->group;
                })->unique('id');
        }
        return view('report-group-semester', [
            'group_id' => $group_id,
            'groups' => $groups,
            'semester_id' => $semester_id,

            'subjects' => $subjects,
            'students' => $students,
            'semester_grades' => $students,
        ]);
    }

    public function saveReportGroupSemester(Request $request)
    {
        $input = $request->all();

        foreach ($input['grades'] as $i => $attendance_option) {
            $str = \explode('|', $i);
            $student_id = $str[0];
            $subject_id = $str[1];
            $existed_grade_semester_id = $str[2];

            $existed_grade_semester = GradeSemester::find($existed_grade_semester_id);


            if ($existed_grade_semester) {
                $existed_grade_semester->attendance_option_id = $attendance_option;
                $existed_grade_semester->save();
            } else {
                $grade_semester = GradeSemester::create([
                    'student_id' => $student_id,
                    'semester_id' => $input['semester_id'],
                    'attendance_option_id' => $attendance_option,
                    'subject_id' => $subject_id,
                ]);
            }
        }

        \Alert::add('success', 'Данные успешно сохранены');

        return redirect()->route('report.group.semester', [
            'group_id' => $input['group_id'],
            'semester_id' => $input['semester_id'],
        ]);
    }
}
