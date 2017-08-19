@extends('layouts.main')
@section('page_name', 'Login')
@section('content')
    @if(session('security_code') and session('categories_grid'))
        <style>
            .images_categories {
                max-width: 700px;
                margin: 0 auto;
            }
            .images_categories input {
                display: none;
            }
            .images_categories input + label{
                opacity: 0.5;
            }
            .images_categories input:checked + label{
                opacity: 1;
            }
        </style>
        <form action="{{config('ems.prefix') . $enterprise->namespace}}/security/confirm/code" method="post" class="images_categories">
            {{csrf_field()}}
            @foreach(session('categories_grid') as $itemt)
                @if(!($loop->iteration % 3))
                    <div class="row">
                        @endif
                        <div class="col-xs-4">
                            <input id="cat_id_{{$loop->iteration}}" type="checkbox" name="cat_id[]" max="3" value="{{$itemt}}">
                            <label for="cat_id_{{$loop->iteration}}"><img src="{{config('ems.prefix') . $enterprise->namespace}}/security/image/?id={{$loop->iteration}}&v={{str_random(10)}}" alt="1" class="img-thumbnail" width="200"></label>
                        </div>
                        @if(!($loop->iteration % 3))
                    </div>
                @endif
            @endforeach
            <button type="submit" class="btn btn-primary">
                Submit
            </button>
        </form>
    @elseif(session('security_code'))
        <div class="col-md-8 col-md-offset-2">
            {{ session('security_code') }}
            <div class="panel panel-default">
                <div class="panel-heading">Enter security code</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{config('ems.prefix') . $enterprise->namespace}}/security/confirm/code">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="confirm" class="col-md-4 control-label">Confirmation code</label>

                            <div class="col-md-6">
                                <input id="confirm" type="text" class="form-control" name="confirm" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="trusted" {{ old('remember') ? 'checked' : '' }}> This is a trusted device
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Confirm
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @else
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">Login</div>
            <div class="panel-body">

                <form class="form-horizontal" method="POST" action="{{config('ems.prefix') . $enterprise->namespace}}/security/authorization/login">
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('login') ? ' has-error' : '' }}">
                        <label for="login" class="col-md-4 control-label">Login or E-Mail Address</label>

                        <div class="col-md-6">
                            <input id="login" type="text" class="form-control" name="login" value="{{ old('login') }}" required autofocus>

                            @if ($errors->has('login'))
                                <span class="help-block">
                                            <strong>{{ $errors->first('login') }}</strong>
                                        </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password" class="col-md-4 control-label">Password</label>

                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control" name="password" required>

                            @if ($errors->has('password'))
                                <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-8 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                Login
                            </button>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endsection