@extends('layouts.main')
@section('page_name', 'Import user')
@section('content')
    @if (session('users_arr'))
        <div class="col-lg-12">
            <h2>Select fields from CSV file</h2>
            @if (session('missing_field'))
                <div class="alert alert-danger">
                    You must select a {{session('missing_field')}} column
                </div>
            @endif
            <form action="" method="post">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                    <tr>
                        <th></th>
                        @for($i = 0, $users_arr = session('users_arr'); $i<count($users_arr[0]); $i++)
                            <th><select name="fields[]" class="form-control">
                                    <option value="">Select field</option>
                                    <option value="first_name">First name</option>
                                    <option value="last_name">Last name</option>
                                    <option value="login">Login</option>
                                    <option value="email">Email</option>
                                    <option value="department">Department name</option>
                                </select>
                            </th>
                        @endfor
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users_arr as $user)
                        <tr>
                            <td>
                                <input type="checkbox" name="selected_users[]" value="{{$loop->index}}" checked>
                            </td>
                            @foreach($user as $item)
                                <td>{{$item}}</td>
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
                <button type="submit" name="back_to_import" value="1" class="btn btn-default">Back to select file</button>
                <button type="submit" name="csv_fields" value="1" class="btn btn-primary">Import selected users</button>
                {{csrf_field()}}
            </form>

        </div>
    @else
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Imposrt from CSV</div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('users_csv') ? ' has-error' : '' }}">
                                <label for="users_csv" class="col-md-4 control-label">Select CSV file</label>

                                <div class="col-md-6">
                                    <input id="users_csv" type="file" class="form-control" name="users_csv">
                                    @if ($errors->has('users_csv'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('users_csv') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Import
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection