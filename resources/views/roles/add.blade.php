@extends('layouts.main')
@section('page_name', "Add new role")
@section('content')
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">Create role</div>
            <div class="panel-body">
                <form class="form-horizontal" method="POST" action="">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label for="name" class="col-md-4 control-label">Name</label>
                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="col-md-4 control-label">Description</label>
                        <div class="col-md-6">
                            <input id="description" type="text" class="form-control" name="description" value="{{ old('description') }}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="action" class="col-md-4 control-label">Module/Controller/Action.</br> You can add several actions.</br> Just hold Ctrl button</label>
                        <div class="col-md-6">
                            <select size="17" name="actions[]" id="action" class="form-control" multiple="" required>
                                @foreach($action_arr as $item)
                                <option value="{{$item['action_id']}}">{{$item['full_path']}}</option>
                                    @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-8 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                Create new role
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
