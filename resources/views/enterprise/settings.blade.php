@extends('layouts.main')
@section('page_name', 'Enterprise settings')
@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            Changes were successfully saved
        </div>
    @endif
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">Company information</div>
            <div class="panel-body">
                <form class="form-horizontal" method="post" action="saveSettings">
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('ent_name') ? ' has-error' : '' }}">
                        <label for="ent_name" class="col-md-4 control-label">Enterprise name</label>

                        <div class="col-md-6">
                            <input id="ent_name" type="text" class="form-control" name="ent_name" value="{{old('ent_name') ?: $enterprise->name}}" required>
                            @if ($errors->has('ent_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('ent_name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                        <label for="description" class="col-md-4 control-label">Description</label>

                        <div class="col-md-6">
                            <input id="description" type="text" class="form-control" name="description" value="{{old('description') ?: $enterprise->description}}" required>
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
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">Theme settings</div>
            <div class="panel-body">
                <form class="form-horizontal" method="post" action="">
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('main_background') ? ' has-error' : '' }}">
                        <label for="main_background" class="col-md-4 control-label">Main background</label>

                        <div class="col-md-6">
                            <input id="main_background" type="color" class="form-control" name="main_background" value="{{ old('main_background') ?: $theme['main_background'] }}" required>

                            @if ($errors->has('main_background'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('main_background') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('side_background') ? ' has-error' : '' }}">
                        <label for="side_background" class="col-md-4 control-label">Side background</label>

                        <div class="col-md-6">
                            <input id="side_background" type="color" class="form-control" name="side_background" value="{{ old('side_background') ?: $theme['side_background'] }}" required>

                            @if ($errors->has('side_background'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('side_background') }}</strong>
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
                            <button class="btn btn-warning preview-theme">
                                Preview
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">Logo</div>
            <div class="panel-body">
                <form class="form-horizontal" method="post" action="logo" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('ent_logo') ? ' has-error' : '' }}">
                        <label for="ent_logo" class="col-md-4 control-label">Logo</label>

                        <div class="col-md-6">
                            <input id="ent_logo" type="file" class="form-control" name="ent_logo" required>

                            @if ($errors->has('ent_logo'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('ent_logo') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            $('.preview-theme').click(function () {
                var main_bg = $('#main_background').val();
                var side_bg = $('#side_background').val();
                $('#page-wrapper').css('background', main_bg);
                $('.side-nav').css('background', side_bg);
                return false;
            })
        })
    </script>
@endsection