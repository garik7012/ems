@extends('layouts.main')
@section('page_name', 'List of positions')
@section('content')
    <div class="col-lg-12">
        <h2>Enterprise's position list</h2>
        <div class="table-responsive">
            @if(count($positions))
                <table class="table table-bordered table-hover table-striped" id="positions-table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Default</th>
                        <th>Is active</th>
                        <th>Edit</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($positions as $position)
                        <tr class="{{$position->is_active ? '': 'danger'}}">
                            <td>{{$position->id}}</td>
                            <td>{{$position->name}}</td>
                            <td>{{$position->description}}</td>
                            <td><span class="fa {{$position->is_default ? 'fa-check' : ''}}"></span></td>
                            <td width="150">@if($position->is_active)
                                    Yes <a href="{{config('ems.prefix') . $enterprise->namespace}}/Enterprises/Positions/deactivate/{{$position->id}}" class="btn btn-danger">deactivate</a>
                                @else
                                    No &nbsp; <a href="{{config('ems.prefix') . $enterprise->namespace}}/Enterprises/Positions/activate/{{$position->id}}" class="btn btn-primary">activate</a>
                                @endif</td>
                            <td><a href="{{config('ems.prefix') . $enterprise->namespace}}/Enterprises/Positions/edit/{{$position->id}}" class="btn btn-primary">Edit</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <h2>There are no positions</h2>
            @endif
        </div>
    </div>
@endsection