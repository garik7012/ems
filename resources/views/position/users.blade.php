@extends('layouts.main')
@section('page_name', "Users and positions")
@section('content')
    <div class="col-lg-12">
        <h2>Users and positions list</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                    <th>User ID</th>
                    <th>Login</th>
                    <th>First name</th>
                    <th>Last name</th>
                    <th>Positions</th>
                    <th>Change</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users_and_positions as $user_and_positions)
                    <tr>
                        <td width="150">{{$user_and_positions[0]->id}}</td>
                        <td width="150">{{$user_and_positions[0]->login}}</td>
                        <td width="150">{{$user_and_positions[0]->first_name}}</td>
                        <td width="150">{{$user_and_positions[0]->last_name}}</td>
                        <td>@foreach($user_and_positions as $item)
                                @if($item->position)
                                    <span class="text-primary users-positions">{{$item->position}}; </span>
                                @endif
                            @endforeach
                        </td>
                        <td><a href="{{config('ems.prefix') . $enterprise->namespace}}/Users/Positions/editUsersPositions/{{$user_and_positions[0]->id}}">Change</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection