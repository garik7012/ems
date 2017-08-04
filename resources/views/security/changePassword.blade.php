@extends('layouts.main')
@section('page_name', 'Change password')
@section('content')
    @if(session('security_code'))
        <div class="col-md-8 col-md-offset-2">
            {{ session('security_code') }}
            <div class="panel panel-default">
                <div class="panel-heading">Enter security code</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="/e/{{$enterprise->namespace}}/security/confirm/code">
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
                <div class="panel-heading">Change your password</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="/e/{{$enterprise->namespace}}/user/changePassword">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('old_password') ? ' has-error' : '' }}">
                            <label for="old_password" class="col-md-4 control-label">Old password</label>

                            <div class="col-md-6">
                                <input id="old_password" type="password" class="form-control" name="old_password" required>

                                @if ($errors->has('old_password'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('old_password') }}</strong>
                                        </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            </br>
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
                                <span>{{$password_policy->description}}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Change Password
                                </button>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection