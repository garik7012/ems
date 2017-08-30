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
    <div class="row">
    <div class="col-sm-5 col-sm-offset-1">
        <h2>Welcome {{Auth::user()->first_name}} {{Auth::user()->last_name}}!</h2>
        @if(Auth::user()->is_superadmin)
            <h3>You are admin!</h3>
        @endif
        @if(Auth::user()->expire_end_at)
            <h3 class="text-danger">Your account expired at {{Auth::user()->expire_end_at}}</h3>
        @endif
        @if($positions and count($positions) > 1)
            <h3>Your positions are:</h3>
            <ul>
            @foreach($positions as $position)
                <li class="dashboard-text">{{$position->name}}</li>
            @endforeach
            </ul>
        @elseif($positions and count($positions) == 1)
            <h3>Your position is: {{$positions[0]->name}}</h3>
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
        @if(count($roles))
        <h3>Your roles are:</h3>
        <ul>
            @foreach($roles as $role)
                <li class="dashboard-text">{{$role->name}} ({{$role->description}})</li>
            @endforeach
        </ul>
        @endif
    </div>
        <div class="col-sm-6">
            <h3>People like you:</h3>
            @if(count($people))
            <ul class="dashboard-text">
                @foreach($people as $item)
                    <li>{{$item->first_name}} {{$item->last_name}}</li>
                @endforeach
            </ul>
            @else
                <p class="dashboard-text">There is no one</p>
            @endif
        </div>
    </div>
@endsection