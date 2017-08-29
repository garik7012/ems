@extends('layouts.main')
@section('page_name', 'Dashboard')
@section('style')
    .dashboard-text {
        font-size: 18px;
    }
    .dashboard-content {
        padding-left: 10px;
    }
@endsection
@section('content')
    <div class="dashboard-content">
        <h2>Welcome {{Auth::user()->first_name}} {{Auth::user()->last_name}}!</h2>
        @if(Auth::user()->is_superadmin)
            <h3>You are admin!</h3>
        @endif
        @if(Auth::user()->expire_end_at)
            <h3 class="text-danger">Your account expired at {{Auth::user()->expire_end_at}}</h3>
        @endif
        @if(Auth::user()->positions and count(Auth::user()->positions) > 1)
            <h3>Your positions are:</h3>
            <ul>
            @foreach(Auth::user()->positions as $position)
                <li class="dashboard-text">{{$position->name}}</li>
            @endforeach
            </ul>
        @elseif(Auth::user()->positions and count(Auth::user()->positions) == 1)
            <h3>Your position is: {{Auth::user()->positions[0]->name}}</h3>
        @endif
        @if(Auth::user()->department)
            <h3>Your department is {{Auth::user()->department->name}}</h3>
        @endif
        @if(Auth::user()->branch)
            <h3>Your branch is {{Auth::user()->branch->name}}</h3>
        @endif
        @if ($is_supervisor)
            <h3>You are supervisor</h3>
        @endif
    </div>
@endsection