@extends('layouts.main')
@section('page_name', 'Actions Logs')
@section('content')
    <div class="col-lg-12">
        <h2>Logs of actions</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped logs-logins">
                <thead>
                <tr>
                    <th>#<span></span></th>
                    <th>User_id<span></span></th>
                    <th>Action_id<span></span></th>
                    <th>Data<span></span></th>
                    <th>User Agent<span></span></th>
                    <th>IP<span></span></th>
                    <th>Created at<span></span></th>
                </tr>
                </thead>
                <tbody>
                @foreach($login_stats as $login_stat)
                    <tr>
                        <td>{{$loop->iteration + $page_c}}</td>
                        <td>{{$login_stat->user_id}}</td>
                        <td>{{$login_stat->action_id}}</td>
                        <td style="word-break: break-all;">{{$login_stat->data}}</td>
                        <td>{{$login_stat->user_agent}}</td>
                        <td>{{$login_stat->ip}}</td>
                        <td>{{$login_stat->created_at}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="show-pagination">
        {{$login_stats->render()}}
    </div>
@endsection