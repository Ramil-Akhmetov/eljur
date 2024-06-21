@php use App\Models\Attendance; @endphp
@extends(backpack_view('blank'))

@section('header')
    <section class="header-operation container-fluid animated fadeIn d-flex mb-2 align-items-baseline d-print-none"
             bp-section="page-header">
        <h1 class="text-capitalize mb-0" bp-section="page-heading">Электронный журнал</h1>
    </section>
@endsection

@section('content')
    <div class="container">
        {{--    erorr    --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


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
            @endif
            @if($group_id && $subject_id)
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

        <style>
            table {
                text-align: center;
            }

            table > thead > tr > th {
                vertical-align: middle;
            }
        </style>

        @if($students && $group_id && $subject_id && count($lessons))
            <form method="POST">
                @csrf
                <div class="table-responsive mt-3" id="journal-table">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th rowspan="3" width="50px">№</th>
                            <th rowspan="3" width="10px">Код</th>
                            <th rowspan="3" style="min-width: 300px">ФИО</th>
                            {{--                        @foreach(\App\Models\Semester::orderBy('number', 'asc')->get() as $semester)--}}
                            {{--                            <th colspan="">--}}
                            {{--                                Семестр {{ $semester->number}}--}}
                            {{--                            </th>--}}
                            {{--                        @endforeach--}}
                        </tr>
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
                                <th colspan="{{ $month['count'] }}">{{ $month['month'] }}</th>
                                <th rowspan="2" width="20px">Средняя</th>
                                <th rowspan="2">Ежемесячная</th>
                            @endforeach
                        </tr>
                        <tr>
                            @foreach($lessonsByMonth as $month => $lessons)
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
                            @endforeach
                        </tr>
                        </thead>
                        <tbody id="journal-body">
                        @foreach($students as $i => $student)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $student->code }}</td>
                                <td>{{ $student->user->surname }} {{ $student->user->name }} {{ $student->user->patronymic }}</td>

                                @foreach($lessonsByMonth as $month => $lessons)
                                    @foreach($lessons as $lesson)
                                        @php
                                            $attendance = \App\Models\Attendance::where('student_id', $student->id)
                                                ->where('lesson_id', $lesson->id)
                                                ->first();
                                        @endphp
                                        <td style="padding: 0; width: 45px;">
                                            <select
                                                class="form-select form-select-sm d-flex bg-light rounded-0"
                                                style="height: 50px; width: 65px; padding: 1rem;"
                                                name="attendances[{{ $student->id }}|{{ $lesson->id }}|{{ $attendance ? $attendance->id : '' }}]"
                                            >
                                                @foreach(\App\Models\AttendanceOption::where('type', 'attendance')->get() as $option)
                                                    <option
                                                        value="{{ $option->id }}"
                                                        {{ ($attendance && $option->id == $attendance->attendance_option_id) ? 'selected' : '' }}
                                                    >
                                                        {{ $option->short_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                    @endforeach
                                    @php
                                        $monthlyGrade = \App\Models\GradeMonth::where('student_id', $student->id)
                                            ->where('subject_id', $subject_id) // Подставьте нужный идентификатор предмета
                                            ->whereMonth('date', $lessons->first()->date->month)
                                            ->whereYear('date', $lessons->first()->date->year)
                                            ->first();

                                        $averageGradesNumbers = \App\Models\Attendance::where('student_id', $student->id)
                                            ->whereIn('lesson_id', $lessons->pluck('id'))
                                            ->whereHas('attendanceOption', function($query) {
                                                $query->whereIn('short_name', ['2', '3', '4', '5']);
                                            })
                                            ->get()
                                            ->map(function($attendance) {
                                                return (int)$attendance->attendanceOption->short_name;
                                            });
                                        $averageGrade =  $averageGradesNumbers->count() != 0 ? $averageGradesNumbers->sum() / $averageGradesNumbers->count() : null;
                                    @endphp
                                    <td>{{ $averageGrade ? number_format($averageGrade, 2) : '-' }}</td> {{-- Ячейка для средней оценки за месяц --}}
                                    <td style="padding: 0; width: 45px;">
                                        <select
                                            class="form-select form-select-sm d-flex bg-light rounded-0"
                                            style="height: 50px; width: 100%; padding: 1rem"
                                            name="grades[{{ $student->id }}|{{ $lesson->id }}|{{ $monthlyGrade ? $monthlyGrade->id : '' }}]"
                                        >
                                            @foreach(\App\Models\AttendanceOption::where('type', 'grade')->get() as $option)
                                                <option
                                                    value="{{ $option->id }}"
                                                    {{ ($monthlyGrade && $option->id == $monthlyGrade->attendance_option_id) ? 'selected' : '' }}
                                                >
                                                    {{ $option->short_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>

                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary mt-3" type="submit">Сохранить</button>
                </div>
            </form>
            @endif
            </form>
        @php
        @endphp
            @if($group_id && $subject_id && backpack_user()->teacher && $subjects->where('id', $subject_id)->first())
                <form method="POST" action="{{route('eljur.add')}}">
                    @csrf
                    <h3>Создать новую лекцию</h3>
                    <div class="d-flex flex-column">
                        <div>
                            <label for="date">Дата</label>
                            <input class="form-control @error('date') is-invalid @enderror" type="date" id="date"
                                   name="date"
                                   value="{{ date('Y-m-d') }}">
                        </div>
                        <div>
                            <label for="classroom">Кабинет</label>
                            <select class="form-select @error('classroom') is-invalid @enderror" id="classroom"
                                    name="classroom_id">
                                @foreach(\App\Models\Classroom::orderBy('number', 'ASC')->get() as $classroom)
                                    <option value="{{ $classroom->id }}">{{ $classroom->number }}
                                        - {{$classroom->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="lesson_type">Тип</label>
                            <select class="form-select @error('lesson_type') is-invalid @enderror" id="lesson_type"
                                    name="lesson_type_id">
                                @foreach(\App\Models\LessonType::all() as $lessonType)
                                    <option value="{{ $lessonType->id }}">{{ $lessonType->name }}
                                        ({{ $lessonType->short_name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="topic">Тема</label>
                            <input type="text" id="topic" name="topic"
                                   class="form-control @error('topic') is-invalid @enderror">
                            @error('topic')
                            <small style="color: red"> {{ $message }}</small>
                            @enderror
                        </div>
                        <div>
                            <label for="count">Количество</label>
                            <input type="number" id="count" name="count" value="2"
                                   class="form-control @error('count') is-invalid @enderror">
                            @error('count')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <input type="number" name="subject_id" value="{{$subject_id}}"
                               class="d-none form-control @error('count') is-invalid @enderror">
                        <input type="number" name="group_id" value="{{$group_id}}"
                               class="d-none form-control @error('count') is-invalid @enderror">
                        <input type="number" name="start_date" value="{{$start_date}}"
                               class="d-none form-control @error('count') is-invalid @enderror">
                        <input type="number" name="end_date" value="{{request('end_date')}}"
                               class="d-none form-control @error('count') is-invalid @enderror">


                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary mt-3" type="submit">Создать</button>
                        </div>
                    </div>
                </form>
    </div>
    @endif
@endsection
