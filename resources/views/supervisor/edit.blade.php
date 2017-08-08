@extends('layouts.main')
@section('page_name', 'Supervisor edit')
@section('content')


    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Change supervisor's subordinates</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="post" action="{{config('ems.prefix') . $enterprise->namespace}}/Enterprises/Supervisors/edit/{{$supervisor->id}}">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="Auth_type" class="col-md-4 control-label">Supervisor login</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" disabled name="supervisor" value="{{$supervisor->login}}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="pwd_policy" class="col-md-4 control-label">Users</label>
                            <div class="col-md-6">
                                <select id="pwd_policy" class="form-control" name="users_id[]" multiple size="10" required>
                                    @foreach($users as $user)
                                        <option value={{$user->id}} {{in_array($user->id, $current_users) ? 'selected': ''}}>
                                            {{$user->first_name}} {{$user->last_name}}({{$user->login}})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Save changes
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
    </div>
@endsection