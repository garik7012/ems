@extends('layouts.main')
@section('page_name', "Create")
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Create new rule</div>
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
                                        @if($is_users)
                                            @foreach($table_items as $item)
                                                <option value="{{$item->id}}">{{$user->first_name}} {{$user->last_name}} ({{$user->login}})</option>
                                            @endforeach
                                        @else
                                            @foreach($table_items as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        @endif
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
                                    <a href="{{config('ems.prefix') . $enterprise->namespace}}/Users/UsersAndControllers/create" class="btn btn-primary">
                                        back
                                    </a>
                                    <button type="submit" name="create" value="true" class="btn btn-success">
                                        create
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