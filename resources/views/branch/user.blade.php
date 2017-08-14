@extends('layouts.main')
@section('page_name', "Change")
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Change branch of user</div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="post" action="">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('branch_id') ? ' has-error' : '' }}">
                                <label for="branch_id" class="col-md-4 control-label">Select branch</label>

                                <div class="col-md-6">
                                    <select id="branch_id" type="text" class="form-control" name="branch_id">
                                        <option value="">No branch</option>
                                        @foreach($branches as $branch)
                                            <option value="{{$branch->id}}" {{$branch->id == $user->branch_id ? 'selected': ''}}>
                                                {{$branch->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('branch_id'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('branch_id') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <a href="{{config('ems.prefix') . $enterprise->namespace}}/Users/Branches/showList/" class="btn btn-primary">
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