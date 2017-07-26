@extends('layouts.main')
@section('content')
    <div class="container">
        <h2>Welcome to {{$enterprise->name}} enterprise</h2>
        <h3>our namespase is {{$enterprise->namespace}}</h3>
        <div class="row">
            @if(Auth::guest())
                <div class="col-md-8 col-md-offset-2">
                 <div class="panel panel-default">
                    <div class="panel-heading">Login</div>
                    <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="/security/login">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
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

                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    Forgot Your Password?
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
                 </div>
                </div>
            @else
                <ul>
                    <li>
                        <h3>Branches</h3>
                        <h4><a href="{{$enterprise->namespace}}/branches/create">Create new Branche</a></h4>
                    </li>
                    <li>
                        <h3>Departments</h3>
                        <h4><a href="{{$enterprise->namespace}}/departments/create">Create new Department</a></h4>
                    </li>
                    <li>
                        <h3>ExternalOrganizations</h3>
                        <h4><a href="">Create new ExternalOrganization</a></h4>
                    </li>
                    <li>
                        <h3>Positions</h3>
                        <h4><a href="">Create new Position</a></h4>
                    </li>
                    <li>
                        <h3><a href="">Settings</a></h3>
                    </li>

                </ul>





            @endif
        </div>
    </div>

@endsection