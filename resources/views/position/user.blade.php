@extends('layouts.main')
@section('page_name', "Change")
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Change positions of user</div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="post" action="">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('positions_id') ? ' has-error' : '' }}">
                                <label for="positions_id" class="col-md-4 control-label">Select position(s)</label>

                                <div class="col-md-6">
                                    <select id="positions_id" type="text" class="form-control" name="positions_id[]" multiple required>
                                        <option value="" {{!count($user_positions) ? 'selected': ''}}>Without position</option>
                                        @foreach($positions as $position)
                                            <option value="{{$position->id}}" {{in_array($position->id, $user_positions) ? 'selected': ''}}>
                                                {{$position->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('positions_id'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('positions_id') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <a href="{{config('ems.prefix') . $enterprise->namespace}}/Users/Positions/showList/" class="btn btn-primary">
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