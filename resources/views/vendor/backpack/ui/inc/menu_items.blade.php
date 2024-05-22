{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i
            class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<x-backpack::menu-item title="Пользователи" icon="la la-user" :link="backpack_url('user')" />

<x-backpack::menu-item title="Кабинеты" icon="la la-question" :link="backpack_url('classroom')" />

<x-backpack::menu-item title="Specialties" icon="la la-question" :link="backpack_url('specialty')" />
<x-backpack::menu-item title="Groups" icon="la la-group" :link="backpack_url('group')" />
<x-backpack::menu-item title="Students" icon="la la-graduation-cap" :link="backpack_url('student')" />
<x-backpack::menu-item title="Teachers" icon="la la-chalkboard-teacher" :link="backpack_url('teacher')" />

<x-backpack::menu-item title="Lesson types" icon="la la-question" :link="backpack_url('lesson-type')" />
<x-backpack::menu-item title="Attendance options" icon="la la-question" :link="backpack_url('attendance-option')" />