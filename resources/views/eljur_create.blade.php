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
        <p class="ms-2 ml-2 mb-0" id="datatable_info_stack" bp-section="page-subheading">Выберите группу и специальность</p>
    </section>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <label for="groupSelect">Выберите группу</label>
            <select id="groupSelect" class="form-control">
                <option value="">Выберите группу</option>
                @foreach ($groups as $group)
                    <option value="{{ $group->id }}">{{ $group->code }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label for="specialtySelect">Специальность</label>
            <select id="specialtySelect" class="form-control" disabled>
                <option value="">Сначала выберите группу</option>
            </select>
        </div>
    </div>

    <div class="row mt-3" id="journalTableContainer" style="display: none;">
        <div class="col-md-12">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>ФИО студента</th>
                    <!-- Add dynamically the date columns -->
                </tr>
                </thead>
                <tbody id="journalTableBody">
                <!-- Student rows will be dynamically added here -->
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('after_scripts')
    <script>
        document.getElementById('groupSelect').addEventListener('change', function() {
            const groupId = this.value;

            if (groupId) {
                fetch(`/eljur/specialties/${groupId}`)
                    .then(response => response.json())
                    .then(data => {
                        const specialtySelect = document.getElementById('specialtySelect');
                        specialtySelect.innerHTML = `<option value="${data.id}">${data.name}</option>`;
                        specialtySelect.disabled = false;
                    });
            } else {
                document.getElementById('specialtySelect').innerHTML = '<option value="">Сначала выберите группу</option>';
                document.getElementById('specialtySelect').disabled = true;
            }
        });

        document.getElementById('specialtySelect').addEventListener('change', function() {
            const groupId = document.getElementById('groupSelect').value;
            const specialtyId = this.value;

            if (specialtyId) {
                fetch(`/eljur/journal/${groupId}/${specialtyId}`)
                    .then(response => response.json())
                    .then(data => {
                        const journalTableContainer = document.getElementById('journalTableContainer');
                        const journalTableBody = document.getElementById('journalTableBody');

                        journalTableBody.innerHTML = '';
                        journalTableContainer.style.display = 'block';

                        data.students.forEach(student => {
                            const row = document.createElement('tr');
                            const cell = document.createElement('td');
                            cell.textContent = `${student.user.surname} ${student.user.name} ${student.user.patronymic}`;
                            row.appendChild(cell);
                            journalTableBody.appendChild(row);
                        });

                        // Dynamically add date columns
                        const dateColumns = data.subjects.map(subject => {
                            return `<th>${subject.name}</th>`;
                        }).join('');

                        document.querySelector('thead tr').innerHTML += dateColumns;
                    });
            } else {
                document.getElementById('journalTableContainer').style.display = 'none';
            }
        });
    </script>
@endsection
