@extends('layouts.main')
@section('page_name', "Create a new branch")
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Create a new branch</div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="post" action="{{config('ems.prefix') . $enterprise->namespace}}/Enterprises/Branches/create">
                            {{ csrf_field() }}
                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label">Branch name</label>

                                <div class="col-md-6">
                                    <input id="name" type="name" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

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
                                    <input id="country" type="text" class="form-control" name="country" value="{{ old('country') }}" required>

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
                                    <input id="city" type="text" class="form-control" name="city" value="{{ old('city') }}" required>

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
                                    <input id="postal_code" type="text" class="form-control" name="postal_code" value="{{ old('postal_code') }}" required>

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
                                    <input id="address_1" type="text" class="form-control" name="address_1" value="{{ old('address_1') }}" required>

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
                                    <input id="address_2" type="text" class="form-control" name="address_2" value="{{ old('address_2') }}">

                                    @if ($errors->has('address_2'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('address_2') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('is_main') ? ' has-error' : '' }}">
                                <label for="is_main" class="col-md-4 control-label">Is main</label>

                                <div class="col-md-6">
                                    <input type="hidden" name="is_main" value="0">
                                    <input id="is_main" type="checkbox" name="is_main" value="{{ old('is_main')? old('is_main'): 1}}">
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