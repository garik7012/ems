@extends('layouts.main')
@section('page_name', "Change")
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Change department of user</div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="post" action="">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('department_id') ? ' has-error' : '' }}">
                                <label for="department_id" class="col-md-4 control-label">Select department</label>

                                <div class="col-md-6">
                                    <select id="department_id" type="text" class="form-control" name="department_id">
                                        <option value="">No department</option>
                                        @foreach($departments as $department)
                                            <option value="{{$department->id}}" {{$department->id == $user->department_id ? 'selected': ''}}>
                                                {{$department->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('department_id'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('department_id') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <a href="{{config('ems.prefix') . $enterprise->namespace}}/Users/Departments/showList/" class="btn btn-primary">
                                        back
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        set
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