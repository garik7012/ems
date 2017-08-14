@extends('layouts.main')
@section('page_name', "Users and branches")
@section('content')
    <div class="col-lg-12">
        <h2>Users and their branch</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                    <th>User ID</th>
                    <th>Login</th>
                    <th>First name</th>
                    <th>Last name</th>
                    <th>Branch</th>
                    <th>Change</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users_and_branches as $item)

                    <tr>
                        <td width="150">{{$item->id}}</td>
                        <td width="150">{{$item->login}}</td>
                        <td width="150">{{$item->first_name}}</td>
                        <td width="150">{{$item->last_name}}</td>
                        <td>{{$item->branch}}</td>
                        <td><a href="{{config('ems.prefix') . $enterprise->namespace}}/Users/Branches/editUsersBranch/{{$item->id}}">Change</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection