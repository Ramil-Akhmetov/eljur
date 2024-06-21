@php use App\Models\Attendance;use App\Models\GradeMonth;use App\Models\TeacherGroupSubject; @endphp
@extends(backpack_view('blank'))

@section('header')
    <section class="header-operation container-fluid animated fadeIn d-flex mb-2 align-items-baseline d-print-none"
             bp-section="page-header">
        <h1 class="text-capitalize mb-0" bp-section="page-heading">Ежемесячная ведомость</h1>
    </section>
@endsection

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
                        <label for="month">Месяц</label>
                        <input class="form-control" type="month" id="month" name="month"
                               onchange="this.form.submit()"
                               value="{{ $month }}">
                    </div>
                </div>
            @endif
        </form>


        @if($students && $group_id && $month)
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
                                            $dateTime = DateTime::createFromFormat('Y-m', $month);
                                            $month_grad = GradeMonth::where('student_id', $student->id)
                                            ->where('subject_id', $subject->id)
                                            ->whereYear('date', $dateTime->format('Y'))
                                            ->whereMonth('date', $dateTime->format('m'))
                                            ->first();
                                        @endphp

                                        @if($month_grad)
                                            {{$month_grad->attendanceOption->short_name}}
                                        @else
                                            -
                                        @endif
                                    </td>
                                @endforeach

                                @php
                                    // Query to count absences
                                    $min_date = $month . '-01';
                                    $max_date = $month . '-31';

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
            </form>
    @endif
@endsection
