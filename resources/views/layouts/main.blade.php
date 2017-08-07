<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'UMS') }}</title>

    <!-- Bootstrap Core CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('css/sb-admin.css') }}" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="{{ asset('font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

<div id="wrapper">
    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{config('ems.prefix') . $enterprise->namespace}}">Enterprise management system <i class="fa fa-long-arrow-right" aria-hidden="true"></i> {{$enterprise->name}}</a>
        </div>
        <!-- Top Menu Items -->
        @include('layouts.topMenu')
        <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
        @if(Auth::user() && (!Session::has('security_code')))
            @include('layouts.sidebar')
        @else
            <style>
                #wrapper{
                    padding: 5px!important;
                }
            </style>
        @endif
        <!-- /.navbar-collapse -->
    </nav>

    <div id="page-wrapper">

        <div class={{Auth::user() && (!Session::has('security_code')) ? "container-fluid": "container"}}>

            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                        @yield('page_name', 'Welcome')
                    </h1>
                </div>
            </div>
            <!-- /.row -->

            <div class="row">
                @yield('content')
            </div>
            <!-- /.row -->

        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->


<!-- jQuery -->
<script src="{{ asset('js/jquery.js') }}"></script>

<!-- Bootstrap Core JavaScript -->
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $('ul.side-nav a').each(function(){
            if($(this))
            if (this.href == document.URL) {
                $(this).parent().addClass('current_link');
                $(this).parent().parent().addClass('in');
            }
        });
        $('ul.side-nav>li').click(function () {
            $('ul.side-nav .in').removeClass('in');
        })
    });
</script>
</body>

</html>
