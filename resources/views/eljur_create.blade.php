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
    <div class="container">
        <div class="form-group mt-3" id="group-section">
            <label for="group-select">Группа:</label>
            <select id="group-select" class="form-control">
                <option value="">Выберите группу</option>
            </select>
        </div>
        <div class="form-group mt-3" id="subject-section">
            <label for="subject-select">Дисциплина:</label>
            <select id="subject-select" class="form-control">
                <option value="">Выберите дисциплину</option>
            </select>
        </div>

        <div class="table-responsive mt-3" id="journal-table">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Месяц</th>
                    <th colspan="2">Сентябрь</th>
                    <th colspan="2">Октябрь</th>
                </tr>
                </thead>
                <tbody id="journal-body">
                <tr>
                    <th>Число</th>
                    <td>1</td>
                    <td>2</td>
                    <td>1</td>
                    <td>2</td>
                </tr>
                <tr>
                    <th>Тема</th>
                    <td>1</td>
                    <td>2</td>
                    <td>1</td>
                    <td>2</td>
                </tr>
                <tr>
                    <td>ФИО</td>
                    <td style="padding: 0; width: 45px"><input type="text" class="form-control d-flex bg-light" style="margin: 0; height: 45px"></td>
                    <td style="padding: 0; width: 45px"><input type="text" class="form-control d-flex bg-light" style="margin: 0; height: 45px"></td>
                    <td style="padding: 0; width: 45px"><input type="text" class="form-control d-flex bg-light" style="margin: 0; height: 45px"></td>
                    <td style="padding: 0; width: 45px"><input type="text" class="form-control d-flex bg-light" style="margin: 0; height: 45px"></td>
                </tr>
                <tr>
                    <td>ФИО</td>
                    <td style="padding: 0; width: 45px"><input type="text" class="form-control d-flex bg-light" style="margin: 0; height: 45px"></td>
                    <td style="padding: 0; width: 45px"><input type="text" class="form-control d-flex bg-light" style="margin: 0; height: 45px"></td>
                    <td style="padding: 0; width: 45px"><input type="text" class="form-control d-flex bg-light" style="margin: 0; height: 45px"></td>
                    <td style="padding: 0; width: 45px"><input type="text" class="form-control d-flex bg-light" style="margin: 0; height: 45px"></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('after_scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const groupId = urlParams.get('group_id');
            if (groupId) {
                fetch(`/eljur/subjects/${groupId}`)
                    .then(response => response.json())
                    .then(data => {
                        const subjectSelect = document.getElementById('subject-select');
                        data.forEach(subject => {
                            const option = document.createElement('option');
                            option.value = subject.id;
                            option.textContent = subject.name;
                            subjectSelect.appendChild(option);
                        });
                        document.getElementById('subject-section').style.display = 'block';
                    });

                document.getElementById('subject-select').addEventListener('change', function() {
                    const subjectId = this.value;
                    if (subjectId) {
                        loadJournalTable(groupId, subjectId);
                    }
                });
            }
        });

        function loadJournalTable(groupId, subjectId) {
            fetch(`/eljur/journal/${groupId}/${subjectId}`)
                .then(response => response.json())
                .then(data => {
                    const journalBody = document.getElementById('journal-body');
                    journalBody.innerHTML = '';
                    data.students.forEach(student => {
                        const row = document.createElement('tr');
                        const nameCell = document.createElement('td');
                        nameCell.textContent = `${student.user.surname} ${student.user.name} ${student.user.patronymic}`;
                        row.appendChild(nameCell);

                        data.lessonDates.forEach(date => {
                            const dateCell = document.createElement('td');
                            const input = document.createElement('input');
                            input.type = 'number';
                            input.className = 'form-control';
                            input.name = `grades[${student.id}][${date}]`;
                            dateCell.appendChild(input);
                            row.appendChild(dateCell);
                        });
                        journalBody.appendChild(row);
                    });
                    document.getElementById('journal-table').style.display = 'block';
                });
        }
    </script>
@endsection
