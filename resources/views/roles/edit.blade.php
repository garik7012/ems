@extends('layouts.main')
@section('page_name', "Edit role")
@section('content')
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">Edit role</div>
            <div class="panel-body">
                <form class="form-horizontal" method="POST" action="">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label for="name" class="col-md-4 control-label">Role name</label>
                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control" name="name" value="{{ $role->name }}" required autofocus>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="col-md-4 control-label">Description</label>
                        <div class="col-md-6">
                            <input id="description" type="text" class="form-control" name="description" value="{{ $role->description }}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="role_policies" class="col-md-4 control-label">Role policies</label>
                        <div class="col-md-6">
                            @if($role->is_never_expires)
                                <label><input type="checkbox" name="is_never_expires" checked> Is never expired</label>
                                <div class="row role_date">
                                    <br>
                                    <label class="col-md-6{{ $errors->has('expire_begin_at') ? ' has-error' : '' }}">Active from
                                        <input type="date" name="expire_begin_at" class="form-control">
                                        @if ($errors->has('expire_begin_at'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('expire_begin_at') }}</strong>
                                            </span>
                                        @endif</label>
                                    <label class="col-md-6{{ $errors->has('expire_end_at') ? ' has-error' : '' }}"> to
                                        <input type="date" name="expire_end_at" class="form-control">
                                        @if ($errors->has('expire_end_at'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('expire_end_at') }}</strong>
                                            </span>
                                        @endif</label>
                                </div>
                            @else
                                <label><input type="checkbox" name="is_never_expires"> Is never expired</label>
                                <div class="row role_date">
                                    <br>
                                    <label class="col-md-6{{ $errors->has('expire_begin_at') ? ' has-error' : '' }}">Active from
                                        <input type="date" name="expire_begin_at" class="form-control" value="{{$role->expire_begin_at}}">
                                        @if ($errors->has('expire_begin_at'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('expire_begin_at') }}</strong>
                                            </span>
                                        @endif</label>
                                    <label class="col-md-6{{ $errors->has('expire_end_at') ? ' has-error' : '' }}"> to
                                        <input type="date" name="expire_end_at" class="form-control" value="{{$role->expire_end_at}}">
                                        @if ($errors->has('expire_end_at'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('expire_end_at') }}</strong>
                                            </span>
                                        @endif</label>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="action" class="col-md-4 control-label">Module . Controller . Action.</br> You can add several actions.</br> Just hold Ctrl button</label>
                        <div class="col-md-6">
                            <select size="17" name="actions[]" id="action" class="form-control" multiple="" required>
                                @foreach($actions as $action)
                                    <option value="{{$action->id}}" {{in_array($action->id, $role_actions)? "selected": ''}}>{{$action->module}}.{{$action->controller}}.{{$action->action}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-8 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                Edit role
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            if ($('input[name=is_never_expires]').is(':checked')) {
                $('.role_date').hide();
            } else {
                $('.role_date').show();
            }
            $('input[name=is_never_expires]').click(function () {
                $('.role_date').toggle();
            });
            if ($('.role_date label').hasClass('has-error')) {
                $('.role_date').show();
                $('input[name=is_never_expires]').attr('checked', false);
            }
        })
    </script>
@endsection