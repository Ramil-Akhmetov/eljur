@php use App\Models\Attendance;use App\Models\GradeMonth;use App\Models\TeacherGroupSubject; @endphp
@extends(backpack_view('blank'))

@section('header')
    <section class="header-operation container-fluid animated fadeIn d-flex mb-2 align-items-baseline d-print-none"
             bp-section="page-header">
        <h1 class="text-capitalize mb-0" bp-section="page-heading">Промежуточная ведомость</h1>
    </section>
@endsection

@php
$canSave = false;
@endphp

@section('content')
    <style>
        table {
            text-align: center;
        }

        table > thead > tr > th {
            vertical-align: middle;
        }

        td {
            vertical-align: middle;
        }
    </style>
    <div class="container">

        <form class="container" onchange="this.form.submit()" method="GET">
            @if(!backpack_user()->student)
                <div class="form-group mt-3" id="group-section">
                    <label for="group-select">Группа:</label>
                    <select id="group-select" name="group_id" class="form-control" onchange="this.form.submit()">
                        <option value="">Выберите группу</option>
                        @foreach($groups as $group)
                            <option
                                value="{{ $group->id }}" {{ $group->id == request('group_id') ? 'selected' : '' }}>{{ $group->code }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            @if($group_id)
                <div class="mt-3">
                    <div class="">
                        <label for="semester">Семестр</label>
                        <select class="form-select" id="semester"
                                name="semester_id" onchange="this.form.submit()">
                            @foreach(\App\Models\Semester::all() as $semester)
                                <option
                                    value="{{ $semester->id }}" {{$semester->id == $semester_id ? 'selected' : ''}}>{{ $semester->number}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif
        </form>


        @if($students && $group_id && $semester_id)
            <form method="POST">
                @csrf
                <div class="table-responsive mt-3" id="journal-table">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th rowspan="3" width="50px">№</th>
                            <th rowspan="3" width="10px">Код</th>
                            <th rowspan="3" style="min-width: 300px">ФИО</th>
                            @foreach($subjects as $subject)
                                <th colspan="">
                                    {{ $subject->name}}
                                </th>
                            @endforeach
                            <th colspan="2">Пропуски</th> <!-- New columns for absences -->
                        </tr>
                        <tr>
                            @foreach($subjects as $subject)
                                <th colspan="">
                                    @php
                                        $teacher = TeacherGroupSubject::where('group_id', $group_id)
                                        ->whereHas('teacherSubject', function ($query) use ($subject) {
                                            $query->where('subject_id', $subject->id);
                                        })
                                        ->get()->map(function ($item) {
                                            return $item->teacher;
                                        })->unique('id');
                                    @endphp
                                    {{$teacher->first()->user->surname}} {{$teacher->first()->user->name}} {{$teacher->first()->user->patronymic}}
                                </th>
                            @endforeach
                            <th>Уважит.</th>
                            <th>Неуважит.</th>
                        </tr>
                        </thead>
                        <tbody id="journal-body">
                        @foreach($students as $i => $student)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $student->code }}</td>
                                <td>{{ $student->user->surname }} {{ $student->user->name }} {{ $student->user->patronymic }}</td>

                                @foreach($subjects as $subject)
                                    <td style="padding: 0; width: 45px;">
                                        @php
                                            $semester_grade = \App\Models\GradeSemester::where('student_id', $student->id)
                                            ->where('subject_id', $subject->id)
                                            ->where('student_id', $student->id)
                                            ->where('semester_id', $semester_id)
                                            ->first();

                                            $teacher = TeacherGroupSubject::where('group_id', $group_id)->get()->map(function ($item) {
                                                return $item->teacher;
                                            })->unique('id')->first();
                                            $ts = null;
                                            if ($teacher && $subject) {
                                                $ts = \App\Models\TeacherSubject::where('subject_id', $subject->id)->where('teacher_id', $teacher->id)->first();
//                                                $canEdit = backpack_user()->role_id == 1 || (backpack_user()->role_id == 2 && $ts);
                                                    $canEdit = false;
                                                if ($ts) {
                                                    $tgs = \App\Models\TeacherGroupSubject::where('group_id', $group_id)->where('teacher_subject_id', $ts->id)->first();
                                                    if ($tgs) {
                                                        $canEdit = true;
                                                    }
                                                }

                                            }
                                        @endphp
                                        @if(backpack_user()->role_id == 1 || (backpack_user()->role_id == 2 && $canEdit))
                                            @php
                                            $canSave = true;
                                            @endphp
                                            <select
                                                class="form-select form-select-sm d-flex bg-light rounded-0"
                                                style="height: 50px; width: 100%; padding: 1rem;"
                                                name="grades[{{ $student->id }}|{{ $subject->id }}|{{ $semester_grade ? $semester_grade->id : '' }}]"
                                            >
                                                @foreach(\App\Models\AttendanceOption::where('type', 'grade')->get() as $option)
                                                    <option
                                                        value="{{ $option->id }}"
                                                        {{ ($semester_grade && $option->id == $semester_grade->attendance_option_id) ? 'selected' : '' }}
                                                    >
                                                        {{ $option->short_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            {{ $semester_grade ? $semester_grade->attendanceOption->short_name : '-' }}
                                        @endif
                                    </td>
                                @endforeach
                                @php
                                    $semesterNumber = \App\Models\Semester::find($semester_id)->number;
    $startDate = \App\Models\Group::find($group_id)->start_date;

    // Ensure startDate is treated as a DateTime object
    $startDate = new DateTime($startDate);
    $startYear = (int)$startDate->format('Y');

    // Calculate the correct year based on the semester
    $currentYear = $startYear + intdiv($semesterNumber, 2);
    $isSecondHalf = $semesterNumber % 2 == 0;

    if ($isSecondHalf) {
        $min_date = $currentYear . '-01-01';
        $max_date = $currentYear . '-06-30';
    } else {
        $min_date = $currentYear . '-09-01';
        $max_date = $currentYear + 1 . '-01-31';
    }

    // Debugging statement to verify dates (can be removed in production)
//    dd($min_date, $max_date, $startYear, $currentYear);



    // Debugging statement to verify dates (can be removed in production)

                                        $validAbsenceOptionId = \App\Models\AttendanceOption::where('short_name', 'у')->where('type', 'attendance')->first()->id;
                                        $invalidAbsenceOptionId = \App\Models\AttendanceOption::where('short_name', 'н')->where('type', 'attendance')->first()->id;

                                        $validAbsences = Attendance::where('attendance_option_id', $validAbsenceOptionId)
    ->where('student_id', $student->id)
    ->whereHas('lesson', function ($query) use ($min_date, $max_date) {
    $query->where('date', '>=', $min_date)->where('date', '<=', $max_date);
    })
    ->count();

    $invalidAbsences = Attendance::where('attendance_option_id', $invalidAbsenceOptionId)
    ->where('student_id', $student->id)
    ->whereHas('lesson', function ($query) use ($min_date, $max_date) {
    $query->where('date', '>=', $min_date)->where('date', '<=', $max_date);
    })
    ->count();
                                @endphp
                                <td>{{ $validAbsences }}</td>
                                <td>{{ $invalidAbsences }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @if(backpack_user()->role_id == 1 || (backpack_user()->role_id == 2 && $canSave))
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-primary mt-3" type="submit" name="semester_id" value="{{$semester_id}}">Сохранить</button>
                    </div>
                @endif
            </form>
    @endif
@endsection
