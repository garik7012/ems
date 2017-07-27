@extends('layouts.main')
@section('content')
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">Security settings</div>
            <div class="panel-body">
                <form class="form-horizontal" method="POST" action="/e/{{$enterprise->namespace}}/security">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label for="Auth_type" class="col-md-4 control-label">Auth type</label>
                        <div class="col-md-6">
                            <select id="Auth_type" class="form-control" name="auth_type_id">
                                <option value="0" {{$enSec['auth_type_id']==0 ? 'selected': ''}}>2 factor</option>
                                <option value="1" {{$enSec['auth_type_id']==1 ? 'selected': ''}}>2 factor with “trusted devices”</option>
                                <option value="2" {{$enSec['auth_type_id']==2 ? 'selected': ''}}>1 factor (uname/pass)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="maxLAt" class="col-md-4 control-label">Max login attempts</label>
                        <div class="col-md-6">
                            <input id="maxLAt" type="number" class="form-control" name="max_login_attempts" value="{{$enSec['max_login_attempts']}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="max_login_period" class="col-md-4 control-label">Max login perion</label>
                        <div class="col-md-6">
                            <input id="max_login_period" type="number" class="form-control" name="max_login_period" value="{{$enSec['max_login_period']}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="max_hours_ban" class="col-md-4 control-label">Max hours ban</label>
                        <div class="col-md-6">
                            <input id="max_hours_ban" type="number" class="form-control" name="max_hours_ban" value="{{$enSec['max_hours_ban']}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password_change_days" class="col-md-4 control-label">Password change days</label>
                        <div class="col-md-6">
                            <input id="password_change_days" type="number" class="form-control" name="password_change_days" value="{{$enSec['password_change_days']}}">
                        </div>
                    </div>
                    <input type="hidden" name="password_policy_id" value="0">

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <div class="checkbox">
                                <label>
                                    <input type="hidden" name="is_sms_allow" value="0">
                                    <input type="checkbox" name="is_sms_allow" {{$enSec['is_sms_allow']  ? 'checked' : '' }}> Is SMS allow?
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