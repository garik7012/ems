@extends('layouts.main')
@section('page_name', "Users and controllers")
@section('content')
    <div class="col-lg-12">
        @if(count($u_and_c))
        <h2>List</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>User</th>
                    <th>Controller</th>
                    <th>Item</th>
                    <th>Delete</th>
                    <th>Edit</th>
                </tr>
                </thead>
                <tbody>
                @foreach($u_and_c as $item)
                    <tr>
                        <td>{{$item->id}}</td>
                        <td>{{$item->first_name}} {{$item->last_name}} ({{$item->login}})</td>
                        <td>{{$item->module}}.{{$item->controller}}</td>
                        <td>
                        @foreach($item->fields as $field)
                            {{$field . ':' . $item->item_name[0]->$field}};
                        @endforeach
                        </td>

                        <td>
                            <a href="{{config('ems.prefix') . $enterprise->namespace}}/Users/UsersAndControllers/delete/{{$item->id}}" class="btn btn-danger">Delete</a>
                        </td>
                        <td><a href="{{config('ems.prefix') . $enterprise->namespace}}/Users/UsersAndControllers/edit/{{$item->id}}" class="btn btn-primary">Edit</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @else
            <h2>There is nothing here</h2>
        @endif
        <a href="{{config('ems.prefix') . $enterprise->namespace}}/Users/UsersAndControllers/create" class="btn btn-success">Create new</a>
    </div>
@endsection
