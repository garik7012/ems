@extends('layouts.main')
@section('page_name', 'List of departments')
@section('content')
    <style>
        .treetable-indent{position:relative;display:inline-block;width:16px;height:16px}.treetable-expander{position:relative;display:inline-block;width:16px;height:16px;cursor:pointer}
    </style>
    <div class="col-lg-12">
        <h2>Enterprise's department list</h2>
        <div class="table-responsive">
            @if(count($departments))
                <table class="table table-bordered table-hover table-striped" id="departments-table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Is active</th>
                        <th>Edit</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($departments as $department)
                        <tr class="{{$department->is_active ? '': 'danger'}}" data-node="treetable-{{$department->id}}" {{$department->parent_id ? "data-pnode=treetable-parent-{$department->parent_id}" : ""}}>
                            <td>{{$department->id}}</td>
                            <td>{{$department->name}}</td>
                            <td>{{$department->description}}</td>
                            <td width="150">@if($department->is_active)
                                    Yes <a href="{{config('ems.prefix') . $enterprise->namespace}}/Enterprises/Departments/deactivate/{{$department->id}}" class="btn btn-danger">deactivate</a>
                                @else
                                    No &nbsp; <a href="{{config('ems.prefix') . $enterprise->namespace}}/Enterprises/Departments/activate/{{$department->id}}" class="btn btn-primary">activate</a>
                                @endif</td>
                            <td><a href="{{config('ems.prefix') . $enterprise->namespace}}/Enterprises/Departments/edit/{{$department->id}}" class="btn btn-primary">Edit</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <h2>There are no departments</h2>
            @endif
        </div>
    </div>
@endsection
@section('script')
    <script src="/js/plugins/bootstrap-treefy.js"></script>
    <script>
        $(function() {
            $("#departments-table").treeFy();
        });
    </script>
@endsection