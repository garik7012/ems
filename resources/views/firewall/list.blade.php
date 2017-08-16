@extends('layouts.main')
@section('page_name', 'Firewall')
@section('content')
    <div class="col-lg-12">
        <h2>Firewall</h2>
        <div class="table-responsive">
            @if(count($firewall_users))
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>IP_from</th>
                        <th>IP_to</th>
                        <th>action</th>
                        <th>note</th>
                        <th>priority</th>
                        <th>is_active</th>
                        <th>edit</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($firewall_users as $item)
                        <tr class="{{$item->is_active ? '': 'danger'}}">
                            <td>{{$loop->iteration}}</td>
                            <td>{{$item->first_name}} {{$item->last_name}} ({{$item->login}})</td>
                            <td>{{$item->ip_from}}</td>
                            <td>{{$item->ip_to}}</td>
                            <td>{{$item->action}}</td>
                            <td>{{$item->note}}</td>
                            <td>{{$item->priority}}</td>
                            <td width="150">@if($item->is_active)
                                    Yes <a href="{{config('ems.prefix') . $enterprise->namespace}}/Users/Firewall/deactivate/{{$item->id}}" class="btn btn-danger">deactivate</a>
                                @else
                                    No &nbsp; <a href="{{config('ems.prefix') . $enterprise->namespace}}/Users/Firewall/activate/{{$item->id}}" class="btn btn-primary">activate</a>
                                @endif</td>
                            <td><a href="{{config('ems.prefix') . $enterprise->namespace}}/Users/Firewall/edit/{{$item->id}}" class="btn btn-primary">Edit</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <h2>There is nothing here</h2>
            @endif
        </div>
    </div>
@endsection