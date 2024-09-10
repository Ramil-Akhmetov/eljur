@extends(backpack_view('blank'))

@php
    $defaultBreadcrumbs = [
        trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
        'Электронный журнал' => false,
    ];
    $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
    <section class="header-operation container-fluid animated fadeIn d-flex mb-2 align-items-baseline d-print-none" bp-section="page-header">
        <h1 class="text-capitalize mb-0" bp-section="page-heading">Электронный журнал</h1>
        <p class="ms-2 ml-2 mb-0" id="datatable_info_stack" bp-section="page-subheading">Оценки студентов</p>
    </section>
@endsection

@section('content')
    <form class="container" onchange="this.form.submit()">
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

        @if($group_id)
            <div class="form-group mt-3" id="subject-section">
                <label for="subject-select">Дисциплина:</label>
                <select id="subject-select" name="subject_id" class="form-control" onchange="this.form.submit()">
                    <option value="">Выберите дисциплину</option>
                    @foreach(\App\Models\Group::find(request('group_id'))->specialty->subjects as $subject)
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
                    <label>Выберите период</label>
                    <div class="d-flex">
                        <div>
                            <label for="start_date">Начало:</label>
                            <input class="form-control" type="date" id="start_date" name="month">
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($group_id && $subject_id)
            <div class="table-responsive mt-3" id="journal-table">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th rowspan="2">№</th>
                        <th rowspan="2">Код</th>
                        <th rowspan="2">ФИО</th>
                        <th colspan="3">1 семестр</th>
                    </tr>
                    <tr>
                        <th colspan="2">Сентябрь</th>
                    </tr>
                    </thead>
                    <tbody id="journal-body">
                    @foreach($students as $i => $student)
                        <tr>
                            <td>{{$i + 1}}</td>
                            <td>{{$student->code}}</td>
                            <td>{{$student->user->surname}} {{$student->user->name}} {{$student->user->patronymic}}</td>
{{--                            @foreach()--}}

{{--                            @endforeach--}}


                            <td style="padding: 0; width: 45px">
                                <select class="form-select form-select-sm d-flex bg-light rounded-0"
                                        style="margin: 0; height: 45px; width: 60px; padding: 0; appearance: none; -webkit-appearance: none; -moz-appearance: none;">
                                    @foreach(\App\Models\AttendanceOption::all() as $option)
                                        <option value="{{ $option->id }}">{{ $option->short_name }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </form>
@endsection
