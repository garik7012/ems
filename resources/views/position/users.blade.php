@extends('layouts.main')
@section('page_name', "Users and positions")
@section('content')
    <div class="col-lg-12">
        <h2>Users and positions list</h2>
        @if(isset($users_and_positions))
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
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
                            <td width="150">{{$loop->iteration + $page_c}}</td>
                            <td width="150">{{$user_and_positions->login}}</td>
                            <td width="150">{{$user_and_positions->first_name}}</td>
                            <td width="150">{{$user_and_positions->last_name}}</td>
                            <td>@foreach($user_and_positions->positions->where('is_active', 1) as $item)
                                    @if($item)
                                        <span class="text-primary users-positions">{{$item->name}}; </span>
                                    @endif
                                @endforeach
                            </td>
                            <td><a href="{{config('ems.prefix') . $enterprise->namespace}}/Users/Positions/editUsersPositions/{{$user_and_positions->id}}">Change</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <h3>No positions yet</h3>
        @endif
        <div class="show-pagination">
            {{$users_and_positions->render()}}
        </div>
    </div>
@endsection