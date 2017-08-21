@extends('layouts.main')
@section('page_name', 'Emals Logs')
@section('content')
    <div class="col-lg-12">
        <h2>Logs of emails</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped logs-logins">
                <thead>
                <tr>
                    <th>#<span></span></th>
                    <th>User<span></span></th>
                    <th>From email<span></span></th>
                    <th>To email<span></span></th>
                    <th>Subject<span></span></th>
                    <th>Data<span></span></th>
                    <th>Created at<span></span></th>
                </tr>
                </thead>
                <tbody>

                @foreach($emails_stats as $email_stat)
                    <tr>
                        <td>{{$loop->iteration + $page_c}}</td>
                        <td>{{$users[$email_stat->user_id]}}</td>
                        <td>{{$email_stat->from_email}}</td>
                        <td>{{$email_stat->to_email}}</td>
                        <td>{{$email_stat->subject}}</td>
                        <td class="text_review" style="word-break: break-all;">{{base64_decode($email_stat->data)}}</td>
                        <td>{{$email_stat->created_at}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="show-pagination">
        {{$emails_stats->render()}}
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