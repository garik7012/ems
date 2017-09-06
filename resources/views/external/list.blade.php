@extends('layouts.main')
@section('page_name', 'List of external organizations')
@section('content')
    <div class="col-lg-12">
        <h2>External organization list</h2>
        <div class="table-responsive">
            @if(count($externals))
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Namespace</th>
                        <th>Description</th>
                        <th>Is active</th>
                        <th>Edit</th>
                        <th>Add user</th>
                        <th>Go to Ext</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($externals as $external)
                        <tr class="{{$external->is_active ? '': 'danger'}}">
                            <td>{{$loop->iteration}}</td>
                            <td>{{$external->name}}</td>
                            <td>{{$external->namespace}}</td>
                            <td>{{$external->description}}</td>
                            <td width="150">@if($external->is_active)
                                    Yes <a href="{{config('ems.prefix') . $enterprise->namespace}}/Enterprises/ExternalOrganizations/deactivate/{{$external->id}}" class="btn btn-danger">deactivate</a>
                                @else
                                    No &nbsp; <a href="{{config('ems.prefix') . $enterprise->namespace}}/Enterprises/ExternalOrganizations/activate/{{$external->id}}" class="btn btn-primary">activate</a>
                                @endif</td>
                            <td><a href="{{config('ems.prefix') . $enterprise->namespace}}/Enterprises/ExternalOrganizations/edit/{{$external->id}}" class="btn btn-primary">Edit</a></td>
                            <td><a href="{{config('ems.prefix') . $enterprise->namespace}}/Enterprises/ExternalOrganizations/addUser/{{$external->id}}" class="btn btn-success">Add user</a></td>
                            <td>@if(Auth::user()->is_superadmin)
                                <a href="{{config('ems.prefix') . $enterprise->namespace}}/ext/goToExt/{{$external->id}}">Go to</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <h2>There are no external organization</h2>
            @endif
        </div>
    </div>
@endsection