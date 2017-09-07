@extends('layouts.main')
@section('page_name', "Edit position")
@section('content')
    <div class="col-md-8 col-md-offset-2">
        @if (session('success'))
            <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                Changes were successfully saved
            </div>
        @endif
        <div class="panel panel-default">
            <div class="panel-heading">Edit position</div>
            <div class="panel-body">
                <form class="form-horizontal" method="post" action="">
                    {{ csrf_field() }}
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label for="name" class="col-md-4 control-label">Position name</label>

                        <div class="col-md-6">
                            <input id="name" type="name" class="form-control" name="name" value="{{ old('name') ?: $position->name }}" required autofocus>

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
                            <input id="description" type="text" class="form-control" name="description" value="{{ old('description') ?: $position->description }}" required>

                            @if ($errors->has('description'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('is_default') ? ' has-error' : '' }}">
                        <label for="is_default" class="col-md-4 control-label">Is default</label>

                        <div class="col-md-6">
                            <input type="hidden" name="is_default" value="0">
                            <input id="is_default" type="checkbox" name="is_default" value="{{ old('is_default')? old('is_default'): 1}}" {{$position->is_default ? 'checked': ''}}>
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