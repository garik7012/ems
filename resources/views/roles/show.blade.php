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
                    <th>Expires</th>
                    <th>Activate/Deactivate role</th>
                    <th>Edit role</th>
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
                            {{$role->is_never_expires ? 'never': "from $role->expire_begin_at to $role->expire_end_at"}}
                        </td>
                        <td>
                            @if($role->is_active)
                                Yes <a href="{{config('ems.prefix') . $enterprise->namespace}}/Enterprises/Roles/deactivate/{{$role->id}}" class="btn btn-danger">deactivate</a>
                            @else
                                No &nbsp; <a href="{{config('ems.prefix') . $enterprise->namespace}}/Enterprises/Roles/activate/{{$role->id}}" class="btn btn-primary">activate</a>
                            @endif
                        </td>
                        <td><a href="{{config('ems.prefix') . $enterprise->namespace}}/Enterprises/Roles/edit/{{$role->id}}" class="btn btn-primary">edit role</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
