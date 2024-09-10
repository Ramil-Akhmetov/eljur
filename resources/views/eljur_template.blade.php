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
    <div class="container">
        <div class="form-group mt-3" id="subject-section">
            <label for="subject-select">Дисциплина:</label>
            <select id="subject-select" class="form-control" style="display:none; margin-top: 15px;">
                <option value="">Выберите дисциплину</option>
            </select>
        </div>

        <div class="table-responsive mt-3" id="journal-table">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Семестр</th>
                    <th colspan="4">1 семестр</th>
                </tr>
                <tr>
                    <th>Месяц</th>
                    <th colspan="2">Сентябрь</th>
                    <th colspan="2">Октябрь</th>
                </tr>
                </thead>
                <tbody id="journal-body">
                <tr>
                    <th>ФИО \ Число</th>
                    <td>
                        <span class="d-inline-block" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus"
                              data-bs-content="Topic name">
                            1
                        </span>
                    </td>
                    <td>2</td>
                    <td>1</td>
                    <td>2</td>
                </tr>
                <tr>
                    <td>Ф.И.О</td>
                    <td style="padding: 0; width: 45px">
                        <select class="form-select form-select-sm d-flex bg-light rounded-0"
                                style="margin: 0; height: 45px; width: 60px; padding: 0; appearance: none; -webkit-appearance: none; -moz-appearance: none;">
                            @foreach(\App\Models\AttendanceOption::all() as $option)
                                <option value="{{ $option->id }}">{{ $option->short_name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td style="padding: 0; width: 45px">
                        <select class="form-select form-select-sm d-flex bg-light rounded-0"
                                style="margin: 0; height: 45px; width: 60px; padding: 0; appearance: none; -webkit-appearance: none; -moz-appearance: none;">
                            @foreach(\App\Models\AttendanceOption::all() as $option)
                                <option value="{{ $option->id }}">{{ $option->short_name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td style="padding: 0; width: 45px">
                        <select class="form-select form-select-sm d-flex bg-light rounded-0"
                                style="margin: 0; height: 45px; width: 60px; padding: 0; appearance: none; -webkit-appearance: none; -moz-appearance: none;">
                            @foreach(\App\Models\AttendanceOption::all() as $option)
                                <option value="{{ $option->id }}">{{ $option->short_name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td style="padding: 0; width: 45px">
                        <select class="form-select form-select-sm d-flex bg-light rounded-0"
                                style="margin: 0; height: 45px; width: 60px; padding: 0; appearance: none; -webkit-appearance: none; -moz-appearance: none;">
                            @foreach(\App\Models\AttendanceOption::all() as $option)
                                <option value="{{ $option->id }}">{{ $option->short_name }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Ф.И.О</td>
                    <td style="padding: 0; width: 45px">
                        <select class="form-select form-select-sm d-flex bg-light rounded-0"
                                style="margin: 0; height: 45px; width: 60px; padding: 0; appearance: none; -webkit-appearance: none; -moz-appearance: none;">
                            @foreach(\App\Models\AttendanceOption::all() as $option)
                                <option value="{{ $option->id }}">{{ $option->short_name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td style="padding: 0; width: 45px">
                        <select class="form-select form-select-sm d-flex bg-light rounded-0"
                                style="margin: 0; height: 45px; width: 60px; padding: 0; appearance: none; -webkit-appearance: none; -moz-appearance: none;">
                            @foreach(\App\Models\AttendanceOption::all() as $option)
                                <option value="{{ $option->id }}">{{ $option->short_name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td style="padding: 0; width: 45px">
                        <select class="form-select form-select-sm d-flex bg-light rounded-0"
                                style="margin: 0; height: 45px; width: 60px; padding: 0; appearance: none; -webkit-appearance: none; -moz-appearance: none;">
                            @foreach(\App\Models\AttendanceOption::all() as $option)
                                <option value="{{ $option->id }}">{{ $option->short_name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td style="padding: 0; width: 45px">
                        <select class="form-select form-select-sm d-flex bg-light rounded-0"
                                style="margin: 0; height: 45px; width: 60px; padding: 0; appearance: none; -webkit-appearance: none; -moz-appearance: none;">
                            @foreach(\App\Models\AttendanceOption::all() as $option)
                                <option value="{{ $option->id }}">{{ $option->short_name }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

                <h1>Ведомость группы</h1>
                <div class="table-responsive mt-3" id="journal-table">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th rowspan="2">ФИО</th>
                            <th>Предмет 1</th>
                            <th>Предмет 2</th>
                            <th>Предмет 3</th>
                            <th>Предмет 4</th>

                            <th colspan="2">Пропущенно часов</th>
                        </tr>
                        <tr>
                            <th>Преподаватель 1</th>
                            <th>Преподаватель 2</th>
                            <th>Преподаватель 3</th>
                            <th>Преподаватель 4</th>

                            <th>Всего</th>
                            <th>По неуважительной причине</th>
                        </tr>
                        </thead>
                        <tbody id="journal-body">
                        <tr>
                            <td>Ф.И.О</td>
                            <td>1</td>
                            <td>2</td>
                            <td>3</td>
                            <td>4</td>
                            <td>0</td>
                            <td>0</td>
                        </tr>
                        <tr>
                            <td>Ф.И.О</td>
                            <td>1</td>
                            <td>2</td>
                            <td>3</td>
                            <td>4</td>
                            <td>0</td>
                            <td>0</td>
                        </tr>
                        <tr>
                            <td>Ф.И.О</td>
                            <td>1</td>
                            <td>2</td>
                            <td>3</td>
                            <td>4</td>
                            <td>0</td>
                            <td>0</td>
                        </tr>
                        <tr>
                            <td>Количество неуспевающих по предметам</td>
                            <td>1</td>
                            <td>2</td>
                            <td>3</td>
                            <td>4</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-end">Итого пропусков (всего, по неуважительной причине)</td>
                            <td>3</td>
                            <td>4</td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-end">Успеваемость за месяц (количество студентов, %)</td>
                            <td>3</td>
                            <td>4%</td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-end">Качество за месяц (количество студентов, %)</td>
                            <td>3</td>
                            <td>4%</td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-end">Кол-во неуспевающих студентов (на "2" и "н/а")</td>
                            <td>3</td>
                            <td>4%</td>
                        </tr>
                        </tbody>
                    </table>

                    <br>
                                <div>
                                    <p>Итого пропусков (всего, по неуважительной причине): <span>0</span> <span>0%</span></p>
                                    <p>Успеваемость за месяц (количество студентов, %): <span>0</span> <span>0%</span></p>
                                    <p>Качество за месяц (количество студентов, %): <span>0</span> <span>0%</span></p>
                                    <p>Кол-во неуспевающих студентов (на "2" и "н/а"): <span>0</span> <span>0%</span></p>
                                </div>
                </div>

                <br>
                <br>

                <h1>Ведомость студента</h1>
                <div class="table-responsive mt-3" id="journal-table">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th rowspan="2">Предмет</th>
                            <th rowspan="2">Преподаватель</th>
                            <th rowspan="2">Оценка</th>

                            <th colspan="2">Пропущенно часов</th>
                        </tr>
                        <tr>
                            <th>Всего</th>
                            <th>По неуважительной причине</th>
                        </tr>
                        </thead>
                        <tbody id="journal-body">
                        <tr>
                            <td>Ф.И.О</td>
                            <td>Преподававатель 1</td>
                            <td>2</td>
                            <td>0</td>
                            <td>0</td>
                        </tr>
                        <tr>
                            <td>Ф.И.О</td>
                            <td>Преподававатель 2</td>
                            <td>2</td>
                            <td>0</td>
                            <td>0</td>
                        </tr>
                        <tr>
                            <td>Ф.И.О</td>
                            <td>Преподававатель 3</td>
                            <td>2</td>
                            <td>0</td>
                            <td>0</td>
                        </tr>
                        <tr>
                            <td colspan="3">Итого пропусков (всего, по неуважительной причине)</td>
                            <td>0</td>
                            <td>0</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
    </div>
@endsection

@section('after_scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const groupSelect = document.getElementById('group-select');
            const subjectSelect = document.getElementById('subject-select');

            groupSelect.addEventListener('change', async function () {
                const groupId = this.value;
                if (groupId) {
                    try {
                        const response = await fetch(`/api/get-user-subjects/${groupId}`, {
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                            }
                        });
                        const data = await response.json();
                        subjectSelect.innerHTML = '<option value="">Выберите дисциплину</option>';
                        data.subjects.forEach(subject => {
                            const option = document.createElement('option');
                            option.value = subject.id;
                            option.text = subject.name;
                            subjectSelect.appendChild(option);
                        });
                        subjectSelect.style.display = 'block';
                    } catch (error) {
                        console.error('Error fetching subjects:', error);
                    }
                } else {
                    subjectSelect.style.display = 'none';
                }
            });

            // Trigger change event if group_id is preselected from query params
            if (groupSelect.value) {
                groupSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
@endsection
