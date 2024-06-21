{{-- This file is used for menu items by any Backpack v6 theme --}}
{{--@if(backpack_user()->role_id == 1)--}}
{{--    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i--}}
{{--                class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>--}}
{{--@endif--}}

@if(backpack_user()->role)
    <x-backpack::menu-dropdown title="Электронный журнал" icon="la la-school">
        @if(backpack_user()->role_id == 1 || backpack_user()->role_id == 2)
            <x-backpack::menu-dropdown-item title="Журнал преподавателя" icon="la la-book"
                                            :link="backpack_url('eljur')"/>
        @endif
        @if(backpack_user()->role_id == 3)
            <x-backpack::menu-dropdown-item title="Журнал студента" icon="la la-book"
                                            :link="backpack_url('eljur/student')"/>
        @endif
    </x-backpack::menu-dropdown>

    <x-backpack::menu-dropdown title="Ведомости" icon="la la-school">
        <x-backpack::menu-dropdown-item title="Ежемесячные ведомости" icon="la la-book"
                                        :link="backpack_url('report-group-month')"/>
        <x-backpack::menu-dropdown-item title="Промежуточные ведомости" icon="la la-book"
                                        :link="backpack_url('report-group-semester')"/>
    </x-backpack::menu-dropdown>

    @if(backpack_user()->role_id == 1)
        <x-backpack::menu-dropdown title="Пользователи" icon="la la-group">
            <x-backpack::menu-dropdown-item title="Пользователи" icon="la la-user" :link="backpack_url('user')"/>
            <x-backpack::menu-dropdown-item title="Пригласительные коды" icon="la la-envelope-open-text"
                                            :link="backpack_url('invite-code')"/>
            <x-backpack::menu-dropdown-item title="Преподаватели" icon="la la-chalkboard-teacher"
                                            :link="backpack_url('teacher')"/>
            <x-backpack::menu-dropdown-item title="Группы" icon="la la-user-friends" :link="backpack_url('group')"/>
            <x-backpack::menu-dropdown-item title="Студенты" icon="la la-graduation-cap"
                                            :link="backpack_url('student')"/>
        </x-backpack::menu-dropdown>
    @endif

    @if(backpack_user()->role_id == 1)
        <x-backpack::menu-dropdown title="Учебный план" icon="la la-school">
            <x-backpack::menu-dropdown-item title="Специальности" icon="la la-drafting-compass"
                                            :link="backpack_url('specialty')"/>
            <x-backpack::menu-dropdown-item title="Дисциплины" icon="la la-ruler-combined"
                                            :link="backpack_url('subject')"/>
            <x-backpack::menu-dropdown-item title="Связи преподавателей и групп" icon="la la-book"
                                            :link="backpack_url('teacher-group-subject')"/>
        </x-backpack::menu-dropdown>
    @endif

    @if(backpack_user()->role_id == 1)
        <x-backpack::menu-dropdown title="Параметры курсов" icon="la la-cog">
            <x-backpack::menu-dropdown-item title="Кабинеты" icon="la la-door-open" :link="backpack_url('classroom')"/>
            <x-backpack::menu-dropdown-item title="Типы лекций" icon="la la-shapes"
                                            :link="backpack_url('lesson-type')"/>
        </x-backpack::menu-dropdown>
    @endif
@endif
