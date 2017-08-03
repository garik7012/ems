@extends('layouts.main')
@section('page_name', "Users and roles")
@section('content')
    <div class="col-lg-12">
        <h2>Users and roles list</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                    <th>User ID</th>
                    <th>Login</th>
                    <th>First name</th>
                    <th>Last name</th>
                    <th>Roles</th>
                    <th>Change</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users_and_roles as $item)

                    <tr>
                        <td width="150">{{$item['user']->id}}</td>
                        <td width="150">{{$item['user']->login}}</td>
                        <td width="150">{{$item['user']->first_name}}</td>
                        <td width="150">{{$item['user']->last_name}}</td>

                        <td>@foreach($item['roles'] as $role)
                            @if($role)
                                <span class="text-primary users-roles" title="{{$role['description']}}">{{$role['name']}}; </span>
                            @endif
                            @endforeach
                        </td>
                        <td><a href="/e/{{$enterprise->namespace}}/Users/Roles/showRolesOfUser/{{$item['user']->id}}">Change</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection