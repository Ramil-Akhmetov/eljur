{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i
            class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<x-backpack::menu-dropdown title="Электронный журнал" icon="la la-school">
    <x-backpack::menu-dropdown-item title="Журнал преподавателя" icon="la la-question" :link="backpack_url('eljur/create')" />
</x-backpack::menu-dropdown>

<x-backpack::menu-dropdown title="Управление пользователями" icon="la la-group">
    <x-backpack::menu-dropdown-item title="Пользователи" icon="la la-user" :link="backpack_url('user')" />
    <x-backpack::menu-dropdown-item title="Пригласительные коды" icon="la la-envelope-open-text" :link="backpack_url('invite-code')" />
    <x-backpack::menu-dropdown-item title="Преподаватели" icon="la la-chalkboard-teacher" :link="backpack_url('teacher')" />
    <x-backpack::menu-dropdown-item title="Группы" icon="la la-user-friends" :link="backpack_url('group')" />
    <x-backpack::menu-dropdown-item title="Студенты" icon="la la-graduation-cap" :link="backpack_url('student')" />
</x-backpack::menu-dropdown>

<x-backpack::menu-dropdown title="Учебный план" icon="la la-school">
    <x-backpack::menu-dropdown-item title="Специальности" icon="la la-question" :link="backpack_url('specialty')" />
    <x-backpack::menu-dropdown-item title="Дисциплины" icon="la la-question" :link="backpack_url('subject')" />
</x-backpack::menu-dropdown>

<x-backpack::menu-dropdown title="Параметры курсов" icon="la la-cog">
    <x-backpack::menu-dropdown-item title="Типы лекций" icon="la la-question" :link="backpack_url('lesson-type')" />
    <x-backpack::menu-dropdown-item title="Типы посещаемости" icon="la la-question" :link="backpack_url('attendance-option')" />
</x-backpack::menu-dropdown>
