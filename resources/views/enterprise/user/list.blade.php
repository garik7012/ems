@extends('layouts.main')
@section('page_name', 'List of users')
@section('content')
    <div class="col-lg-12">
        <h2>Enterprise's users list</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Login</th>
                    <th>First name</th>
                    <th>Last name</th>
                    <th>is active</th>
                    <th>is superadmin</th>
                    <th>Login</th>
                </tr>
                </thead>
                <tbody>
                @foreach($ent_users as $ent_user)
                    <tr {{ $ent_user->is_active == false ? "class=danger": ''}}>
                        <td>{{$ent_user->id}}</td>
                        <td>{{$ent_user->login}}</td>
                        <td>{{$ent_user->first_name}}</td>
                        <td>{{$ent_user->last_name}}</td>
                        <td width="150">
                            @if($ent_user->is_active)
                            Yes <a href="/e/{{$enterprise->namespace}}/Users/Settings/deactivate/{{$ent_user->id}}" class="btn btn-danger">deactivate</a>
                            @else
                            No &nbsp; <a href="/e/{{$enterprise->namespace}}/Users/Settings/activate/{{$ent_user->id}}" class="btn btn-primary">activate</a>
                            @endif
                        </td>
                        <td width="130">{{$ent_user->is_superadmin ? "Yes": ""}}</td>
                        <td><a href="/e/{{$enterprise->namespace}}/user/loginAsUser/{{$ent_user->id}}">Login</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="show-pagination">
    {{$ent_users->render()}}
    </div>
@endsection