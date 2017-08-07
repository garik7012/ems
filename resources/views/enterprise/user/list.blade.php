@extends('layouts.main')
@section('page_name', 'List of users')
@section('content')
    <div class="col-lg-12">
        <h2>Enterprise's user list</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Login</th>
                    <th>First name</th>
                    <th>Last name</th>
                    <th>is active</th>
                    <th>is superadmin</th>
                    <th>Login</th>
                    <th>Settings</th>
                </tr>
                </thead>
                <tbody>
                @foreach($ent_users as $ent_user)
                    <tr {{ $ent_user->is_active == false ? "class=danger": ''}}>
                        <td>{{$ent_user->id}}</td>
                        <td>{{$ent_user->login}}</td>
                        <td>{{$ent_user->first_name}}</td>
                        <td>{{$ent_user->last_name}}</td>
                        <td width="150">
                            @if($ent_user->is_superadmin && !Auth::user()->is_superadmin)
                                @if($ent_user->is_active)
                                    Yes
                                @else
                                    No
                                @endif
                            @else
                                @if($ent_user->is_active)
                                Yes <a href="{{config('ems.prefix') . $enterprise->namespace}}/Users/Settings/deactivate/{{$ent_user->id}}" class="btn btn-danger">deactivate</a>
                                @else
                                No &nbsp; <a href="{{config('ems.prefix') . $enterprise->namespace}}/Users/Settings/activate/{{$ent_user->id}}" class="btn btn-primary">activate</a>
                                @endif
                            @endif
                        </td>
                        <td width="150">
                            @if($ent_user->is_superadmin)
                                Yes
                                @if($ent_user->id != Auth::user()->id and Auth::user()->is_superadmin)
                                    <form action="{{config('ems.prefix') . $enterprise->namespace}}/user/depriveSuperadmin" method="post" style="display: inline-block">
                                        {{csrf_field()}}
                                        <input type="hidden" name="user_id" value="{{$ent_user->id}}">
                                        <button type="submit" class="btn btn-danger">deprive</button>
                                    </form>
                                @endif
                            @else
                                No &nbsp;
                                @if(Auth::user()->is_superadmin)
                                    <form action="{{config('ems.prefix') . $enterprise->namespace}}/user/makeSuperadmin" method="post" style="display: inline-block">
                                        {{csrf_field()}}
                                        <input type="hidden" name="user_id" value="{{$ent_user->id}}">
                                        <button type="submit" class="btn btn-success">make</button>
                                    </form>
                                @endif
                            @endif
                        </td>
                        <td>@if($ent_user->id == Auth::user()->id or ($ent_user->is_superadmin && !Auth::user()->is_superadmin))
                            @else
                            <a href="{{config('ems.prefix') . $enterprise->namespace}}/Enterprises/Enterprise/loginAsUser/{{$ent_user->id}}">Login</a>
                            @endif
                        </td>

                        <td>@if($ent_user->is_superadmin && !Auth::user()->is_superadmin)
                            @else
                            <a href="{{config('ems.prefix') . $enterprise->namespace}}/Enterprises/Users/showUserSettings/{{$ent_user->id}}">Change</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="show-pagination">
    {{$ent_users->render()}}
    </div>
@endsection