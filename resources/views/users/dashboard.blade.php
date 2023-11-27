@extends('layout')

@section('content')

<!-- Dashboard Wrapper -->
<div class="d-flex" id="dashboard-wrapper">

    <!-- Sidebar -->
    <div id="sidebar-wrapper" class="bg-light border-right">
        <div class="sidebar-heading">@lang('messages.dashboard')</div>
        <div class="list-group list-group-flush">
            <a href="{{ url('/absenceExcuses/get') }}" class="list-group-item list-group-item-action {{ request()->is('absenceExcuses/*') ? 'active' : 'bg-light' }}">@lang('messages.absence_excuses')</a>
            <a href="{{ url('/courseApologyExcuses/get') }}" class="list-group-item list-group-item-action {{ request()->is('courseApologyExcuses/*') ? 'active' : 'bg-light' }}">@lang('messages.course_apology_excuses')</a>
            <a href="{{ url('/semesterApologyExcuses/get') }}" class="list-group-item list-group-item-action {{ request()->is('semesterApologyExcuses/*') ? 'active' : 'bg-light' }}">@lang('messages.semester_apology_excuses')</a>
            <a href="{{ url('/medicalExcuses/get') }}" class="list-group-item list-group-item-action {{ request()->is('medicalExcuses/*') ? 'active' : 'bg-light' }}">@lang('messages.medical_excuses')</a>
            <a href="{{ url('/deprivations/get') }}" class="list-group-item list-group-item-action {{ request()->is('deprivations/*') ? 'active' : 'bg-light' }}">@lang('messages.deprivations')</a>
        </div>
    </div>

    <!-- Page Content -->
    <div id="page-content-wrapper">
        <div class="container-fluid">
            @if(is_null($data))
                <h1 class="mt-4">@lang('messages.welcome', ['name' => auth()->user()->name])</h1>
                <p>@lang('messages.dashboard_intro')</p>
            @else                  
                @yield('crud')
            @endif
        </div>
    </div>

</div>

@endsection
