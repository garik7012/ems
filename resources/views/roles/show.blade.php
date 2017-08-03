@extends('layouts.main')
@section('page_name', "All roles")
@section('content')
    <div class="col-lg-12">
        <h2>List of roles</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                    <th>Role</th>
                    <th>Description</th>
                    <th>Action path</th>
                    <th>Activate/Deactivate role</th>
                </tr>
                </thead>
                <tbody>

                @foreach($roles as $role)
                    <tr>
                        <td>{{$role->name}}</td>
                        <td>{{$role->description}}</td>
                        <td>@foreach ($role->actions as $action)
                                <p> {{$action['full_path']}}</p>
                        @endforeach
                        </td>
                        <td>
                            @if($role->is_active)
                                Yes <a href="/e/{{$enterprise->namespace}}/Users/Roles/deactivate/{{$role->id}}" class="btn btn-danger">deactivate</a>
                            @else
                                No &nbsp; <a href="/e/{{$enterprise->namespace}}/Users/Roles/activate/{{$role->id}}" class="btn btn-primary">activate</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
