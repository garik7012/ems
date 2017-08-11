@extends('layouts.main')
@section('page_name', "Edit branch")
@section('content')
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">Edit branch</div>
            <div class="panel-body">
                <form class="form-horizontal" method="post" action="">
                    {{ csrf_field() }}
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label for="name" class="col-md-4 control-label">Branch name</label>

                        <div class="col-md-6">
                            <input id="name" type="name" class="form-control" name="name" value="{{ old('name') ?: $branch->name }}" required autofocus>

                            @if ($errors->has('name'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('country') ? ' has-error' : '' }}">
                        <label for="country" class="col-md-4 control-label">Country</label>

                        <div class="col-md-6">
                            <input id="country" type="text" class="form-control" name="country" value="{{ old('country') ?: $branch->country }}" required>

                            @if ($errors->has('country'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('country') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
                        <label for="city" class="col-md-4 control-label">City</label>

                        <div class="col-md-6">
                            <input id="city" type="text" class="form-control" name="city" value="{{ old('city') ?: $branch->city}}" required>

                            @if ($errors->has('city'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('city') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('postal_code') ? ' has-error' : '' }}">
                        <label for="postal_code" class="col-md-4 control-label">Postal code</label>

                        <div class="col-md-6">
                            <input id="postal_code" type="text" class="form-control" name="postal_code" value="{{ old('postal_code') ?: $branch->postal_code}}" required>

                            @if ($errors->has('postal_code'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('postal_code') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('address_1') ? ' has-error' : '' }}">
                        <label for="address_1" class="col-md-4 control-label">Address 1</label>

                        <div class="col-md-6">
                            <input id="address_1" type="text" class="form-control" name="address_1" value="{{ old('address_1') ?: $branch->address_1}}" required>

                            @if ($errors->has('address_1'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('address_1') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('address_2') ? ' has-error' : '' }}">
                        <label for="address_2" class="col-md-4 control-label">Address 2</label>

                        <div class="col-md-6">
                            <input id="address_2" type="text" class="form-control" name="address_2" value="{{ old('address_2') ?: $branch->address_2 }}">

                            @if ($errors->has('address_2'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('address_2') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('latitude') ? ' has-error' : '' }}">
                        <label for="latitude" class="col-md-4 control-label">Latitude</label>

                        <div class="col-md-6">
                            <input id="latitude" type="text" class="form-control" name="latitude" value="{{ old('latitude') ?: $branch->latitude }}">

                            @if ($errors->has('latitude'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('latitude') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('longitude') ? ' has-error' : '' }}">
                        <label for="longitude" class="col-md-4 control-label">Longitude</label>

                        <div class="col-md-6">
                            <input id="longitude" type="text" class="form-control" name="longitude" value="{{ old('longitude') ?: $branch->longitude }}">

                            @if ($errors->has('longitude'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('longitude') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('is_main') ? ' has-error' : '' }}">
                        <label for="is_main" class="col-md-4 control-label">Is main</label>

                        <div class="col-md-6">
                            <input type="hidden" name="is_main" value="0">
                            <input id="is_main" type="checkbox" name="is_main" value="1" {{$branch->is_main ? 'checked': ''}}>
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