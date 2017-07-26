@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Register</div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="post" action="/enterprises/registration">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <div class="col-md-10 text-center">
                                    <h4 class="text-center">Enterprise</h4>
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('e_name') ? ' has-error' : '' }}">
                                <label for="e_name" class="col-md-4 control-label">Enterprise name</label>

                                <div class="col-md-6">
                                    <input id="e_name" type="text" class="form-control" name="e_name" value="{{ old('e_name') }}" required autofocus>

                                    @if ($errors->has('e_name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('e_name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('e_namespace') ? ' has-error' : '' }}">
                                <label for="e_namespace" class="col-md-4 control-label">Namespace</label>

                                <div class="col-md-6">
                                    <input id="e_namespace" type="text" class="form-control" name="e_namespace" value="{{ old('e_namespace') }}" required autofocus>

                                    @if ($errors->has('e_namespace'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('e_namespace') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('e_description') ? ' has-error' : '' }}">
                                <label for="e_description" class="col-md-4 control-label">Description</label>

                                <div class="col-md-6">
                                    <input id="e_description" type="text" class="form-control" name="e_description" value="{{ old('e_description') }}" required autofocus>

                                    @if ($errors->has('e_description'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('e_description') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-10 text-center">
                                    <h4 class="text-center">Admin</h4>
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('login') ? ' has-error' : '' }}">
                                <label for="login" class="col-md-4 control-label">Login</label>

                                <div class="col-md-6">
                                    <input id="login" type="text" class="form-control" name="login" value="{{ old('login') }}" required autofocus>

                                    @if ($errors->has('login'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('login') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                                <label for="first_name" class="col-md-4 control-label">First name</label>

                                <div class="col-md-6">
                                    <input id="first_name" type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" required autofocus>

                                    @if ($errors->has('first_name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('first_name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                                <label for="last_name" class="col-md-4 control-label">Last name</label>

                                <div class="col-md-6">
                                    <input id="last_name" type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" required autofocus>

                                    @if ($errors->has('last_name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('last_name') }}</strong>
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
                                <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Register
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
