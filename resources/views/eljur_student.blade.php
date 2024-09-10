@extends(backpack_view('blank'))

@php
    $defaultBreadcrumbs = [
        trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
        'Электронный журнал' => false,
    ];
    $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
    <section class="header-operation container-fluid animated fadeIn d-flex mb-2 align-items-baseline d-print-none"
             bp-section="page-header">
        <h1 class="text-capitalize mb-0" bp-section="page-heading">Электронный журнал</h1>
        <p class="ms-2 ml-2 mb-0" id="datatable_info_stack" bp-section="page-subheading">Оценки студентов</p>
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
            <div class="form-group mt-3" id="subject-section">
                <label for="subject-select">Дисциплина:</label>
                <select id="subject-select" name="subject_id" class="form-control" onchange="this.form.submit()">
                    <option value="">Выберите дисциплину</option>
                    @foreach($subjects as $subject)
                        <option
                                value="{{ $subject->id }}" {{ $subject->id == request('subject_id') ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            @if($subject_id)
                <div class="mt-3">
                    <div class="d-flex justify-content-between">
                        <label>Период:</label>
                        <div class="d-flex gap-2">
                            <div>
                                <label for="start_date">Начало:</label>
                                <input class="form-control" type="date" id="start_date" name="start_date"
                                       value="{{ $start_date }}" onchange="this.form.submit()">
                            </div>
                            <div>
                                <label for="end_date">Конец:</label>
                                <input class="form-control" type="date" id="end_date" name="end_date"
                                       value="{{ $end_date }}" onchange="this.form.submit()">
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </form>

        @if($subject_id && count($lessons) > 0)
            <div class="table-responsive mt-3" id="journal-table">
                @csrf
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        @php
                            $lessonsByMonth = $lessons->groupBy(function($lesson) {
                                    return $lesson->date->format('F');
                                });

                                $uniqueMonths = $lessonsByMonth->map(function($lessons, $month) {
                                    return [
                                        'month' => $month,
                                        'count' => $lessons->count()
                                    ];
                                });
                        @endphp

                        @foreach($uniqueMonths as $month)
                            <th colspan="{{$month['count']}}">{{$month['month']}}</th>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach($lessons as $lesson)
                            <td>
                                <span class="d-inline-block" tabindex="0" data-bs-toggle="popover"
                                      data-bs-trigger="hover focus"
                                      data-bs-html=true
                                      data-bs-content="
                                        Кабинет: {{$lesson->classroom->number}} - {{$lesson->classroom->name}} <br>
                                        Тема: {{$lesson->topic}} <br>
                                        Тип: {{$lesson->lessonType->name}} ({{$lesson->lessonType->short_name}}) <br>
                                        Преподаватель: {{$lesson->teacher->user->surname}} {{$lesson->teacher->user->name}} {{$lesson->teacher->user->patronymic}}<br>
                                      "
                                >
                                    {{ $lesson->date->format('d') }}
                                </span>
                            </td>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody id="journal-body">
                    <tr>
                        @foreach($lessons as $lesson)
                            @php
                                $attendance = \App\Models\Attendance::where('student_id', backpack_user()->student->id)
                                    ->where('lesson_id', $lesson->id)
                                    ->first();
                            @endphp
                            <td style="padding: 0; width: 45px; height: 45px" class="align-middle">
                                <span class="d-flex justify-content-center">
                                @if($attendance)
                                        {{$attendance->attendanceOption->short_name}}
                                    @else
                                        -
                                    @endif
                                </span>
                            </td>
                        @endforeach
                    </tr>
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
