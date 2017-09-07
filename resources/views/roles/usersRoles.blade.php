@extends('layouts.main')
@section('page_name', "Users and roles")
@section('content')
    <div class="col-lg-12">
        <h2>Users and roles list</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Login</th>
                    <th>First name</th>
                    <th>Last name</th>
                    <th>Roles</th>
                    <th>Change</th>
                    <th>The note</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users_and_roles as $user)

                    <tr>
                        <td width="150">{{$loop->iteration + $page_c}}</td>
                        <td width="150">{{$user->login}}</td>
                        <td width="150">{{$user->first_name}}</td>
                        <td width="150">{{$user->last_name}}</td>

                        <td>@foreach($user->roles as $role)
                            @if($role)
                                <span class="text-primary users-roles" title="{{$role['description']}}">{{$role['name']}}; </span>
                            @endif
                            @endforeach
                        </td>
                        <td><a href="{{config('ems.prefix') . $enterprise->namespace}}/Users/Roles/showRolesOfUser/{{$user->id}}">Change</a></td>
                        <td>
                            {{in_array($user->id, $supervisors_id) ? 'Supervisor; ': ''}}
                            {{in_array($user->id, $u_and_c) ? 'In users and controllers': ''}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="show-pagination">
            {{$users_and_roles->render()}}
        </div>
    </div>
@endsection