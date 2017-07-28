@extends('layouts.main')
@section('page_name', 'List of users')
@section('content')
    <div class="container">
            <div class="col-lg-12">
                <h2>Bordered with Striped Rows</h2>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Login</th>
                            <th>First name</th>
                            <th>Last name</th>
                            <th>is active</th>
                            <th>Login</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($ent_users as $ent_user)
                            <tr {{$ent_user->id == Auth::user()->id ? "class=danger": ''}}>
                                <td>{{$ent_user->id}}</td>
                                <td>{{$ent_user->login}}</td>
                                <td>{{$ent_user->first_name}}</td>
                                <td>{{$ent_user->last_name}}</td>
                                <td>{{$ent_user->is_active ? 'active': 'not active'}}</td>
                                <td><a href="/e/{{$enterprise->namespace}}/user/login-as-user/{{$ent_user->id}}">Login</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        {{$ent_users->render()}}
    </div>
@endsection