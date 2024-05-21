{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i
            class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<x-backpack::menu-item title="Пользователи" icon="la la-question" :link="backpack_url('user')" />

<x-backpack::menu-item title="Кабинеты" icon="la la-question" :link="backpack_url('classroom')" />

<x-backpack::menu-item title="Specialties" icon="la la-question" :link="backpack_url('specialty')" />
<x-backpack::menu-item title="Groups" icon="la la-question" :link="backpack_url('group')" />