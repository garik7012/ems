@extends('layouts.main')
@section('page_name', 'Security settings')
@section('content')
    <div class="col-md-8 col-md-offset-2">
        @if (session('success'))
            <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                Changes were successfully saved
            </div>
        @endif
        <div class="panel panel-default">
            <div class="panel-heading">Security settings</div>
            <div class="panel-body">
                <form class="form-horizontal" method="POST" action="{{config('ems.prefix') . $enterprise->namespace}}/Enterprises/Settings/setSecurity">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label for="Auth_type" class="col-md-4 control-label">Auth type</label>
                        <div class="col-md-6">
                            <select id="Auth_type" class="form-control" name="auth_type_id">
                                @foreach($auth_types as $auth_type)
                                <option value={{$auth_type->id}} {{$enSec['auth_type_id']==$auth_type->id ? 'selected': ''}}>{{$auth_type->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <div class="checkbox">
                                <label>
                                    <input type="hidden" name="is_sms_allow" value="0">
                                    <input type="checkbox" name="is_sms_allow" {{$enSec['is_sms_allow']  ? 'checked' : '' }}> Is SMS allowed?
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="maxLAt" class="col-md-4 control-label">Max login attempts</label>
                        <div class="col-md-6">
                            <input id="maxLAt" type="number" class="form-control" name="max_login_attempts" value="{{$enSec['max_login_attempts']}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="max_login_period" class="col-md-4 control-label">Max login period (min)</label>
                        <div class="col-md-6">
                            <input id="max_login_period" type="number" class="form-control" name="max_login_period" value="{{$enSec['max_login_period']}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="max_hours_ban" class="col-md-4 control-label">Max ban (hours)</label>
                        <div class="col-md-6">
                            <input id="max_hours_ban" type="number" class="form-control" name="max_hours_ban" value="{{$enSec['max_hours_ban']}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password_change_days" class="col-md-4 control-label">Password change after (days)</label>
                        <div class="col-md-6">
                            <input id="password_change_days" type="number" class="form-control" name="password_change_days" value="{{$enSec['password_change_days']}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="pwd_policy" class="col-md-4 control-label">Password policy</label>
                        <div class="col-md-6">
                            <select id="pwd_policy" class="form-control" name="password_policy_id">
                                @foreach($password_policies as $password_policy)
                                    <option value={{$password_policy->id}} {{$enSec['password_policy_id']==$password_policy->id ? 'selected': ''}}>
                                        {{$password_policy->name}}({{$password_policy->description}})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <div class="checkbox">
                                <label>
                                    <input type="hidden" name="self_signup" value="0">
                                    <input type="checkbox" name="self_signup" {{$enSec['self_signup']  ? 'checked' : '' }}> Self signup
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-8 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                Save Changes
                            </button>
                            <button type="reset" class="btn btn-default">
                                Cancel
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection