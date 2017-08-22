@extends('layouts.main')
@section('page_name', 'Success')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h2>The mail was sent to your email</h2>
                <h3>Please check your email and follow the instructions.</h3>
                <br>
                <p>User will receive confirmation link</p>
                <a href="http://{{$confirm}}"><h4>{{$confirm}}</h4></a>
            </div>
        </div>
    </div>
@endsection