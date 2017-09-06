@extends('layouts.main')
@section('page_name', "Add new role")
@section('custom-css')
    @include('layouts.plugins.cssdate')
@endsection
@section('content')
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">Create a role</div>
            <div class="panel-body">
                <form class="form-horizontal" method="POST" action="">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label for="name" class="col-md-4 control-label">Role name</label>
                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="col-md-4 control-label">Description</label>
                        <div class="col-md-6">
                            <input id="description" type="text" class="form-control" name="description" value="{{ old('description') }}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="role_policies" class="col-md-4 control-label">Role policies</label>
                        <div class="col-md-6">
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
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="action" class="col-md-4 control-label">Module/Controller/Action.</br> You can add several actions.</br> Just hold Ctrl button</label>
                        <div class="col-md-6">
                            <select size="17" name="actions[]" id="action" class="form-control" multiple data-selected-text-format="count" required>
                                @foreach($actions as $action)
                                    <option value="{{$action->id}}">{{$action->module}}.{{$action->controller}}.{{$action->action}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-8 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                Create new role
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="/js/plugins/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function () {
           $('.role_date').hide();
           $('input[name=is_never_expires]').click(function () {
               $('.role_date').toggle();
           });
           if ($('.role_date label').hasClass('has-error')) {
               $('.role_date').show();
               $('input[name=is_never_expires]').attr('checked', false);
           }
            //Any browser that does not support the input type date will default to the standard type, which is text
            if ( $('[type="date"]').prop('type') != 'date' ) {
                $('[type="date"]').datepicker();
            }
        });
    </script>
@endsection
