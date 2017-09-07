@extends('layouts.main')
@section('page_name', "Edit department")
@section('content')
    <div class="col-md-8 col-md-offset-2">
        @if (session('success'))
            <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                Changes were successfully saved
            </div>
        @endif
        <div class="panel panel-default">
            <div class="panel-heading">Edit department</div>
            <div class="panel-body">
                <form class="form-horizontal" method="post" action="">
                    {{ csrf_field() }}
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label for="name" class="col-md-4 control-label">Department name</label>

                        <div class="col-md-6">
                            <input id="name" type="name" class="form-control" name="name" value="{{ old('name') ?: $department->name }}" required autofocus>

                            @if ($errors->has('name'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                        <label for="description" class="col-md-4 control-label">Description</label>

                        <div class="col-md-6">
                            <input id="description" type="text" class="form-control" name="description" value="{{ old('description') ?: $department->description }}" required>

                            @if ($errors->has('description'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('parent_id') ? ' has-error' : '' }}">
                        <label for="parent_id" class="col-md-4 control-label">Parent department</label>

                        <div class="col-md-6">
                            <select id="parent_id" type="text" class="form-control" name="parent_id">
                                <option value="">No parent department</option>
                                @foreach($departments as $item)
                                    <option value="{{$item->id}}"
                                    {{$department->parent_id == $item->id ? 'selected': ''}}>{{$item->name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('parent_id'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('parent_id') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                Save Changes
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
@endsection