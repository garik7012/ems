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
            .images_categories .trusted_device {
                font-size: 16px;
            }
            .images_categories .trusted_device input {
                display: block;
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
            @if (session('with_trusted_device'))
            <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                    <div class="checkbox">
                        <label  class="trusted_device">
                            <input type="checkbox" name="trusted" {{ old('remember') ? 'checked' : '' }}> This is the trusted device
                        </label>
                    </div>
                </div>
            </div>
            @endif
            <button type="submit" class="btn btn-primary">
                Submit
            </button>
        </form>
    @elseif(session('security_code'))
        <div class="col-md-8 col-md-offset-2">
            {{ session('security_code_temp') }}
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
                        @if(session('with_trusted_device'))
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="trusted" {{ old('remember') ? 'checked' : '' }}> This is the trusted device
                                    </label>
                                </div>
                            </div>
                        </div>
                        @endif
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
    @if(session('wrong-confirm'))
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            Wrong confirmation code. Please login and try again
        </div>
    @endif
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
                        <div class="col-md-8 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                Login
                            </button>
                            @if($self_signup)
                            <a href="register" class="btn btn-primary">
                                Registration
                            </a>
                            @endif
                            <a class="btn btn-link" href="{{config('ems.prefix') . $enterprise->namespace}}/security/forgotPassword">
                                Forgot Your Password?
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endsection