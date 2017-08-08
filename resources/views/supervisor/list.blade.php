@extends('layouts.main')
@section('page_name', 'List of supervisors')
@section('content')
    <div class="col-lg-12">
        <h2>Supervisor list</h2>
        <div class="table-responsive">
            @if(count($supervisors))
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                    <tr>
                        <th>Supervisor</th>
                        <th>Subordinates</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($supervisors as $supervisor)
                        <tr>
                            <td>{{$supervisor['sup']->first_name . ' ' . $supervisor['sup']->last_name . "(" . $supervisor['sup']->login . ")"}}</td>
                            <td>@foreach($supervisor['sub'] as $subordinate)
                                    {{$subordinate['first_name']}} {{$subordinate['last_name']}}({{$subordinate['login']}});
                                @endforeach
                            </td>
                            <td>
                                <a href="{{config('ems.prefix') . $enterprise->namespace}}/Enterprises/Supervisors/edit/{{$supervisor['sup']->id}}">Edit</a>
                            </td>
                            <td>
                                <a href="{{config('ems.prefix') . $enterprise->namespace}}/Enterprises/Supervisors/delete/{{$supervisor['sup']->id}}">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <h2>There is no supervisors</h2>
            @endif
                <a href="{{config('ems.prefix') . $enterprise->namespace}}/Enterprises/Supervisors/add/" class="btn btn-success">Add supervisor</a>
        </div>
    </div>
@endsection