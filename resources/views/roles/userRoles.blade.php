@extends('layouts.main')
@section('page_name', "Roles of user")
@section('content')
    <div class="col-lg-12">
        <h2>Roles of user</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                    <th>Role</th>
                    <th>Description</th>
                    <th>Add/Delete user's role</th>
                </tr>
                </thead>
                <tbody>

                @foreach($roles as $role)
                    <tr>
                        <td>{{$role->name}}</td>
                        <td>{{$role->description}}</td>
                        @if(in_array(array('role_id' => $role->id), $user_roles_id))
                            <td><form action="{{config('ems.prefix') . $enterprise->namespace}}/Users/Roles/deleteUsersRole" method="post">
                                    <input type="hidden" name="user_id" value="{{$user_id}}">
                                    <input type="hidden" name="role_id" value="{{$role->id}}">
                                    {{csrf_field()}}
                                    <button type="submit" class="btn btn-danger">delete</button>
                                </form>
                            </td>
                        @else
                            <td><form action="{{config('ems.prefix') . $enterprise->namespace}}/Users/Roles/addRoleToUser" method="post">
                                    <input type="hidden" name="user_id" value="{{$user_id}}">
                                    <input type="hidden" name="role_id" value="{{$role->id}}">
                                    {{csrf_field()}}
                                    <button type="submit" class="btn btn-success">Add role to user</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
