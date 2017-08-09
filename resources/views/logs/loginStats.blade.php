@extends('layouts.main')
@section('page_name', 'Logs')
@section('content')
    <div class="col-lg-12">
        <h2>Logs of users' login</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped logs-logins">
                <thead>
                <tr>
                    <th data-order="id">ID<span></span></th>
                    <th data-order="login">Login<span></span></th>
                    <th data-order="user_id">User_id<span></span></th>
                    <th data-order="is_ok">Is_ok<span></span></th>
                    <th data-order="ip">IP<span></span></th>
                    <th data-order="user_agent">User Agent<span></span></th>
                    <th data-order="created_at">Created at<span></span></th>
                </tr>
                </thead>
                <tbody>
                @foreach($login_stats as $login_stat)
                    <tr>
                        <td>{{$login_stat->id}}</td>
                        <td>{{$login_stat->login}}</td></a>
                        <td>{{$login_stat->user_id}}</td>
                        <td><span class="fa {{$login_stat->is_ok ? 'fa-check' : ''}}"></span></td>
                        <td>{{$login_stat->ip}}</td>
                        <td>{{$login_stat->user_agent}}</td>
                        <td>{{$login_stat->created_at}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <form id="orderBy" action="" method="get" style="display: none">
                {{csrf_field()}}
                <input type="hidden" name="orderBy">
                <input type="hidden" name="order" value="{{$orderBy}}">
                <input type="hidden" name="desc" value="{{$desc}}">
            </form>
        </div>
    </div>
    <div class="show-pagination">
        {{$login_stats->render()}}
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            $('.logs-logins th').each(function () {
                if($(this).data('order') == "{{$orderBy}}") {
                    $(this).find('span').addClass('fa');
                    if ('desc' == '{{$desc}}') {
                        $(this).find('span').addClass('fa-caret-down');
                    } else {
                        $(this).find('span').addClass('fa-caret-up');
                    }
                }
            });

            $('.show-pagination a').click(function () {
               $(this).attr('href', $(this).attr('href') + "&order={{$orderBy}}&desc={{$desc}}");
            });
            $('th').click(function () {
                var order = $(this).data('order');
                if (order == $('#orderBy input[name=order]').val()) {
                    $('#orderBy input[name=desc]').val() == 'desc' ? $('#orderBy input[name=desc]').val('asc'): $('#orderBy input[name=desc]').val('desc')
                }
                $('#orderBy input[name=order]').val(order)
                $('#orderBy').submit();
            })
        })
    </script>
@endsection