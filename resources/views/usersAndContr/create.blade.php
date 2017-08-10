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
                                <label for="user_id" class="col-md-4 control-label">Select user</label>

                                <div class="col-md-6">
                                    <select id="user_id" type="text" class="form-control" name="user_id" required>
                                        @foreach($users as $user)
                                            <option value="{{$user->id}}">{{$user->first_name}} {{$user->last_name}} ({{$user->login}})</option>
                                        @endforeach
                                    </select>
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
                                    <select id="controller_id" type="text" class="form-control" name="controller_id" required>
                                        @foreach($controllers as $controller)
                                            <option value="{{$controller->id}}">{{$controller->module}}.{{$controller->controller}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('controller_id'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('controller_id') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" name="next" value="true" class="btn btn-primary">
                                        Next
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