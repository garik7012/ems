@extends('layouts.main')
@section('page_name', 'Please select three different categories')
@section('content')
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">Picture categories</div>
            <div class="panel-body">
                <form class="form-horizontal" method="POST" action="">
                    {{ csrf_field() }}
                    <div class="form-group{{ $errors->has('first_cat') ? ' has-error' : '' }}">
                        <label for="first_cat" class="col-md-4 control-label">First category</label>

                        <div class="col-md-6">
                            <select id="first_cat" class="form-control" name="first_cat" required>
                                <option value="">Select first category</option>
                                @for($i=1; $i<25; $i++)
                                    <option value="{{$i}}" {{old('first_cat') == $i ? 'selected': ''}}>category {{$i}}</option>
                                @endfor
                            </select>
                            @if ($errors->has('first_cat'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('first_cat') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('second_cat') ? ' has-error' : '' }}">
                        <label for="second_cat" class="col-md-4 control-label">Second category</label>

                        <div class="col-md-6">
                            <select id="second_cat" class="form-control" name="second_cat" required>
                                <option value="">Select second category</option>
                                @for($i=1; $i<25; $i++)
                                    <option value="{{$i}}" {{old('second_cat') == $i ? 'selected': ''}}>category {{$i}}</option>
                                @endfor
                            </select>
                            @if ($errors->has('second_cat'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('second_cat') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('third_cat') ? ' has-error' : '' }}">
                        <label for="third_cat" class="col-md-4 control-label">Third category</label>

                        <div class="col-md-6">
                            <select id="third_cat" class="form-control" name="third_cat" required>
                                <option value="">Select third category</option>
                                @for($i=1; $i<25; $i++)
                                    <option value="{{$i}}" {{old('third_cat') == $i ? 'selected': ''}}>category {{$i}}</option>
                                @endfor
                            </select>
                            @if ($errors->has('third_cat'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('third_cat') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-8 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                Save
                            </button>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection