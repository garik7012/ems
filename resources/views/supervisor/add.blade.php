@extends('layouts.main')
@section('page_name', 'Create new supervisor')
@section('content')


    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Add supervisor</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="post" action="{{config('ems.prefix') . $enterprise->namespace}}/Enterprises/Supervisors/add">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="sup_id" class="col-md-4 control-label">Select supervisor</label>
                            <div class="col-md-6">
                                <select id="sup_id" class="form-control" name="supervisor_id" required>
                                    @foreach($users as $user)
                                        <option value={{$user->id}}>
                                            {{$user->first_name}} {{$user->last_name}}({{$user->login}})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="sub_id" class="col-md-4 control-label">Select subordinates</label>
                            <div class="col-md-6">
                                <select id="sub_id" class="form-control" name="subs_id[]" multiple size="10" required>
                                    @foreach($users as $user)
                                        <option value={{$user->id}}>
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