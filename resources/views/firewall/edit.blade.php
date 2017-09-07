@extends('layouts.main')
@section('page_name', 'Edit')
@section('content')

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            @if (session('success'))
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    Changes were successfully saved
                </div>
            @endif
            <div class="panel panel-default">
                <div class="panel-heading">Edit the firewall's rule</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="post" action="">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('user_id') ? ' has-error' : '' }}">
                            <label for="user_id" class="col-md-4 control-label">User</label>
                            <div class="col-md-6">
                                <input class="form-control" type="text" value="{{$user->first_name}} {{$user->last_name}}({{$user->login}})" readonly>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('ip_from') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">IP from</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" maxlength="15"
                                       name="ip_from" value="{{old('ip_from') ? old('ip_from'): $ent_firewall->ip_from}}">
                                @if ($errors->has('ip_from'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('ip_from') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('ip_to') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">IP to</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" maxlength="15"
                                       name="ip_to" value="{{old('ip_to') ? old('ip_to'): $ent_firewall->ip_to}}">
                                @if ($errors->has('ip_to'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('ip_to') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('action') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Action</label>
                            <div class="col-md-6">
                                <select class="form-control" name="action">
                                    <option value="allow" {{(old('action') == 'allow' or $ent_firewall->action == 'action') ? 'selected': ''}}>allow</option>
                                    <option value="block" {{(old('action') == 'block' or $ent_firewall->action == 'block') ? 'selected': ''}}>block</option>
                                </select>
                                @if ($errors->has('action'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('action') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('priority') ? ' has-error' : '' }}">
                            <label for="priority" class="col-md-4 control-label">Priority</label>
                            <div class="col-md-6">
                                <input id="priority" type="number" class="form-control" name="priority" value="{{old('priority') ? old('priority'): $ent_firewall->priority}}" max="100" required>
                                @if ($errors->has('priority'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('priority') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('note') ? ' has-error' : '' }}">
                            <label for="note" class="col-md-4 control-label">Note</label>

                            <div class="col-md-6">
                                <textarea id="note" type="text" class="form-control" name="note">{{old('note') ? old('note'): $ent_firewall->note}}</textarea>
                                @if ($errors->has('note'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('note') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Save changes
                                </button>
                                <button type="reset" class="btn btn-default">
                                    reset
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection