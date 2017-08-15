@extends('layouts.main')
@section('page_name', "External organization")
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Create a new external organization</div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="post" action="{{config('ems.prefix') . $enterprise->namespace}}/Enterprises/ExternalOrganizations/create">
                            {{ csrf_field() }}
                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label">Enterprise name</label>

                                <div class="col-md-6 input-group">
                                    <input id="name" type="name" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('namespace') ? ' has-error' : '' }}">
                                <label for="namespace" class="col-md-4 control-label">Namespace</label>

                                <div class="col-md-6 input-group help-block-beautify">
                                    <span class="input-group-addon">{{$enterprise->namespace}}_</span>
                                    <input id="namespace" type="text" class="form-control" name="namespace" value="{{ old('namespace') }}" required>
                                    @if ($errors->has('namespace'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('namespace') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            @if ($errors->has('namespace'))
                                <span class="help-block">&nbsp;</span>
                            @endif
                            <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                                <label for="description" class="col-md-4 control-label">Description</label>

                                <div class="col-md-6 input-group">
                                    <input id="description" type="text" class="form-control" name="description" value="{{ old('description') }}" required>

                                    @if ($errors->has('description'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Create
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