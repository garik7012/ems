@extends('layouts.main')
@section('page_name', "Users and departments")
@section('content')
    <div class="col-lg-12">
        <h2>Users and their department</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                    <th>User ID</th>
                    <th>Login</th>
                    <th>First name</th>
                    <th>Last name</th>
                    <th>Department</th>
                    <th>Change</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users_and_departments as $item)

                    <tr>
                        <td width="150">{{$item->id}}</td>
                        <td width="150">{{$item->login}}</td>
                        <td width="150">{{$item->first_name}}</td>
                        <td width="150">{{$item->last_name}}</td>
                        <td>{{$item->department}}</td>
                        <td><a href="{{config('ems.prefix') . $enterprise->namespace}}/Users/Departments/editUsersDepartment/{{$item->id}}">Change</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection