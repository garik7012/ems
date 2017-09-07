@extends('layouts.main')
@section('page_name', "Users and departments")
@section('content')
    <div class="col-lg-12">
        <h2>Users and their department</h2>
        @if(isset($users_and_departments))
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                    <th>#</th>
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
                        <td width="150">{{$loop->iteration + $page_c}}</td>
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
        @else
            <h3>No departments yet</h3>
        @endif
        <div class="show-pagination">
            {{$users_and_departments->render()}}
        </div>
    </div>
@endsection