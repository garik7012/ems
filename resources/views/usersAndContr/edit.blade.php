@extends('layouts.main')
@section('page_name', "Edit")
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        Changes were successfully saved
                    </div>
                @endif
                <div class="panel panel-default">
                    <div class="panel-heading">You can change item</div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="post" action="">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('user_id') ? ' has-error' : '' }}">
                                <label for="user_id" class="col-md-4 control-label">User</label>

                                <div class="col-md-6">
                                    <input type="text" disabled class="form-control"
                                           value="{{$user->first_name}} {{$user->last_name}} ({{$user->login}})">
                                    <input type="hidden" name="user_id" value="{{$user->id}}">
                                    @if ($errors->has('user_id'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('user_id') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('controller_id') ? ' has-error' : '' }}">
                                <label for="controller_id" class="col-md-4 control-label">Select controller</label>

                                <div class="col-md-6">
                                    <input type="text" disabled class="form-control" value="{{$controller->module}}.{{$controller->controller}}">
                                    <input type="hidden" name="controller_id" value="{{$controller->id}}">
                                    @if ($errors->has('controller_id'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('controller_id') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('item_id') ? ' has-error' : '' }}">
                                <label for="item_id" class="col-md-4 control-label">Select item</label>

                                <div class="col-md-6">
                                    <select id="item_id" type="text" class="form-control" name="item_id" required>
                                        @foreach($table_items as $item)
                                            <option value="{{$item->id}}">
                                                @foreach($fields as $field)
                                                    {{$field . ':' . $item->$field}};
                                                @endforeach
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('item_id'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('item_id') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" name="create" value="true" class="btn btn-primary">
                                        Save changes
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