@extends('layouts.main')
@section('page_name', 'SMS')
@section('content')
    <div class="col-md-8 col-md-offset-2">
         {{ $token }}
        <div class="panel panel-default">
            <div class="panel-heading">Password reset form</div>
            <div class="panel-body">
                <form class="form-horizontal" method="POST" action="{{config('ems.prefix') . $enterprise->namespace}}/security/checkCode">
                    {{ csrf_field() }}

                    <input type="hidden" name="user_id" value="{{ $user_id }}">

                    <div class="form-group{{ $errors->has('sms_code') ? ' has-error' : '' }}">
                        <label for="sms_code" class="col-md-4 control-label">Code</label>

                        <div class="col-md-6">
                            <input id="sms_code" type="sms_code" class="form-control" name="sms_code" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                Check code
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection