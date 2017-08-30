@extends('layouts.main')
@section('page_name', 'Dashboard')
@section('style')
    .dashboard-text {
        font-size: 18px;
    }
    .dashboard-content {
        padding-left: 10px;
    }
    .dashboard-status {
        color: blue;
        cursor: pointer;
    }
@endsection
@section('content')
    <div class="row">
    <div class="col-sm-5 col-sm-offset-1">
        <h2>Welcome {{Auth::user()->first_name}} {{Auth::user()->last_name}}!</h2>
        <p class="dashboard-text"><b class="dashboard-status">Status: </b> {{$status->value}}</p>

        <form action="" method="post" class="form-inline change_status-form">
            {{csrf_field()}}
            <div class="form-group{{ $errors->has('user_status') ? ' has-error' : '' }}">
            <input type="text" name="user_status" class="form-control" value="{{old('user_status') ?: $status->value}}">
            <button type="submit" class="btn btn-success">{{$status->value ? 'Change status': 'Set status'}}</button>
            @if ($errors->has('user_status'))
                <span class="help-block">
                    <strong>{{ $errors->first('user_status') }}</strong>
                </span>
            @endif
            </div>

        </form>
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
@section('script')
    <script>
        $(document).ready(function () {
            if(!$('.change_status-form .form-group').hasClass('has-error')) $('.change_status-form').hide();
            $('.dashboard-status').click(function () {
                $('.change_status-form').toggle();
            })
        })
    </script>
@endsection