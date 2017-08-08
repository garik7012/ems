@extends('layouts.main')
@section('page_name', 'Login')
@section('content')
    @if(session('security_code'))
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