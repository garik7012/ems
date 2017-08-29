@extends('layouts.main')
@section('page_name', 'Theme Settings')
@section('content')
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