@extends('layouts.main')
@section('page_name', 'User profile')
@section('content')


    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">User panel</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="post" action="{{config('ems.prefix') . $enterprise->namespace}}/Enterprises/Users/changeUsersSettings/{{$user['user_id']}}">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="Auth_type" class="col-md-4 control-label">Auth type</label>
                            <div class="col-md-6">
                                <select id="Auth_type" class="form-control" name="auth_type_id">
                                    <option value='0' {{$user['auth_type_id']==false ? 'selected': ''}}>as in enterprise settings</option>
                                    @foreach($auth_types as $auth_type)
                                        <option value={{$auth_type->id}} {{$user['auth_type_id']==$auth_type->id ? 'selected': ''}}>{{$auth_type->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="pwd_policy" class="col-md-4 control-label">Password policy</label>
                            <div class="col-md-6">
                                <select id="pwd_policy" class="form-control" name="password_policy_id">
                                    <option value='0' {{$user['password_policy_id']==false ? 'selected': ''}}>as in enterprise settings</option>
                                    @foreach($password_policies as $password_policy)
                                        <option value={{$password_policy->id}} {{$user['password_policy_id']==$password_policy->id ? 'selected': ''}}>
                                            {{$password_policy->name}}({{$password_policy->description}})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('expire_end_at') ? ' has-error' : '' }}">
                            <label for="expire_end_at" class="col-md-4 control-label">Set expiration date</label>
                            <div class="col-md-6">
                                <input id="expire_end_at" type="date" class="form-control" name="expire_end_at" value="{{ old('expire_end_at') ? old('expire_end_at'): $user['expire_end_at'] }}">
                                @if ($errors->has('expire_end_at'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('expire_end_at') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <input type="hidden" name="user_id" value="{{$user['user_id']}}">
                                <button type="submit" class="btn btn-primary">
                                    Save changes
                                </button>
                                <button type="reset" class="btn btn-default">
                                    Reset
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection