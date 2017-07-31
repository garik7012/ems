@extends('layouts.main')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h2>Email was sent to user</h2>
                <p>User will receive confirm link</p>
                <a href="http://{{$confirm}}"><h4>{{$confirm}}</h4></a>
                <p>If you want to check how it works: </p>
                <ul>
                    <li>please copy this link</li>
                    <li>logout</li>
                    <li>paste the link and go</li>
                </ul>
            </div>
        </div>
    </div>
@endsection