@extends('layouts.main')
@section('page_name', "IP list")
@section('content')
    <div class="col-lg-12">
        <h2>List of organization's IP</h2>
        <div class="table-responsive">
            @if(count($ent_ips))
                @if($warning)
                    <h2 class="text-danger">Your IP address is not on the list of allowed IP. Please, check, is it correct</h2>
                @endif
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>IP</th>
                        <th>Description</th>
                        <th>Is active</th>
                        <th>Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($ent_ips as $item)
                        <tr class="{{$item->is_active ? '': 'danger'}}">
                            <td>{{$loop->iteration}}</td>
                            <td>{{$item->ristrict_network_ip}}</td>
                            <td>{{$item->description}}</td>
                            <td width="150">@if($item->is_active)
                                    Yes <a href="{{config('ems.prefix') . $enterprise->namespace}}/Security/EnterpriseNetworks/deactivate/{{$item->id}}" class="btn btn-danger">deactivate</a>
                                @else
                                    No &nbsp; <a href="{{config('ems.prefix') . $enterprise->namespace}}/Security/EnterpriseNetworks/activate/{{$item->id}}" class="btn btn-primary">activate</a>
                                @endif</td>
                            <td><a href="{{config('ems.prefix') . $enterprise->namespace}}/Security/EnterpriseNetworks/deleteIP/{{$item->id}}" class="btn btn-danger">Delete</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <h2>There is no item</h2>
            @endif
        </div>

        <h3 class="text-warning">Your current IP is: {{$current_ip}}</h3>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Add IP address</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="post" action="{{config('ems.prefix') . $enterprise->namespace}}/Security/EnterpriseNetworks/addIP">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('ristrict_network_ip') ? ' has-error' : '' }}">
                            <label for="ip_address" class="col-md-4 control-label">IP Address</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="xxx.xxx.xxx.xxx" maxlength="15"
                                       name="ristrict_network_ip" value="{{old('ristrict_network_ip')}}">
                                @if ($errors->has('ristrict_network_ip'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('ristrict_network_ip') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            <label for="description" class="col-md-4 control-label">Description</label>

                            <div class="col-md-6">
                                <textarea id="description" type="text" class="form-control" name="description">{{ old('description') }}</textarea>
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
                                    Add address
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
