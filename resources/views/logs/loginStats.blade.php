@extends('layouts.main')
@section('page_name', 'Logs')
@section('content')
    <div class="col-lg-12">
        <h2>Logs of users' login</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped logs-logins">
                <thead>
                <tr>
                    <th>#<span></span></th>
                    <th>Login<span></span></th>
                    <th>User_id<span></span></th>
                    <th>Is_ok<span></span></th>
                    <th>IP<span></span></th>
                    <th>User Agent<span></span></th>
                    <th>Created at<span></span></th>
                </tr>
                </thead>
                <tbody>
                @foreach($login_stats as $login_stat)
                    <tr>
                        <td>{{$loop->iteration + $page_c}}</td>
                        <td>{{$login_stat->login}}</td></a>
                        <td>{{$login_stat->user_id}}</td>
                        <td><span class="fa {{$login_stat->is_ok ? 'fa-check' : ''}}"></span></td>
                        <td>{{$login_stat->ip}}</td>
                        <td class="text_review">{{base64_decode($login_stat->user_agent)}}</td>
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
@section('script')
    <script>
        jQuery(".text_review").each(function(){
            var review_full = jQuery(this).html();
            var review = review_full;
            if( review.length > 255 ) {
                review = review.substring(0, 255);
                jQuery(this).html( review + '<div class="read_more"> more...</div>' );
            }
            jQuery(this).append('<div class="full_text" style="display: none;">' + review_full + '</div>');
        });
        jQuery(".read_more").click(function(){
            jQuery(this).parent().html( jQuery(this).parent().find(".full_text").html() );
        });
    </script>
@endsection