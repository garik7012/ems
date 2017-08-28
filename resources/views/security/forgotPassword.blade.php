@extends('layouts.main')
@section('page_name', 'Forgot password')
@section('content')
    <div class="col-md-8 col-md-offset-2">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
                {{ session('link') }}
            </div>
        @endif
        <div class="panel panel-default">
            <div class="panel-heading">Reset Password</div>
            <div class="panel-body">

                <form class="form-horizontal" method="POST" action="{{config('ems.prefix') . $enterprise->namespace}}/security/{{$is_sms ? 'sendCode' : 'sendResetLink'}}">
                    {{ csrf_field() }}
                    @if($is_sms)
                        <div class="form-group{{ $errors->has('phone_number') ? ' has-error' : '' }}">
                            <label for="phone_number" class="col-md-4 control-label">Your phone</label>

                            <div class="col-md-6">
                                <input id="phone_number" type="phone_number" class="form-control" name="phone_number" value="{{ old('phone_number') }}" required>

                                @if ($errors->has('phone_number'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('phone_number') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    @else
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
                    @endif
                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                {{$is_sms ? 'Send code' :'Send Password Reset Link'}}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection