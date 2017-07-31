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
            <a class="navbar-brand" href="/e/{{$enterprise->namespace}}">User management system <i class="fa fa-long-arrow-right" aria-hidden="true"></i> {{$enterprise->name}}</a>
        </div>
        <!-- Top Menu Items -->
        <ul class="nav navbar-right top-nav">
            @if(Auth::user())
                @if (Auth::check() && Session::has('auth_from_admin_asd'))
                    <li><a href = "/e/{{$enterprise->namespace}}/user/list/gback">Go back to my profile</a></li>
                @endif
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> {{Auth::user()->first_name}} {{Auth::user()->last_name}} <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="/e/{{$enterprise->namespace}}/user/profile"><i class="fa fa-fw fa-user"></i>Profile</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                <i class="fa fa-fw fa-power-off"></i> Logout
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </li>
            @endif
        </ul>
        @if(Auth::user())
        <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav side-nav">
                <li>
                    <a href="/e/{{$enterprise->namespace}}">Dashboard</a>
                </li>
                <li>
                    <a href="javascript:;" data-toggle="collapse" data-target="#demo">Branches <i class="fa fa-fw fa-caret-down"></i></a>
                    <ul id="demo" class="collapse">
                        <li>
                            <a href="/e/{{$enterprise->namespace}}/branches/list">List</a>
                        </li>
                        @if(Auth::user()->is_superadmin)
                        <li>
                            <a href="/e/{{$enterprise->namespace}}/branches/create">Create</a>
                        </li>
                        @endif
                    </ul>
                </li>
                <li>
                    <a href="javascript:;" data-toggle="collapse" data-target="#demo1">Departments <i class="fa fa-fw fa-caret-down"></i></a>
                    <ul id="demo1" class="collapse">
                        <li>
                            <a href="/e/{{$enterprise->namespace}}/departments/list">List</a>
                        </li>
                        @if(Auth::user()->is_superadmin)
                        <li>
                            <a href="/e/{{$enterprise->namespace}}/departments/create">Create</a>
                        </li>
                        @endif
                    </ul>
                </li>
                @if(Auth::user()->is_superadmin)
                    <li>
                        <a href="/e/{{$enterprise->namespace}}/security">Security</a>
                    </li>
                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#demo2">Users <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="demo2" class="collapse">
                            <li>
                                <a href="/e/{{$enterprise->namespace}}/user/list">List</a>
                            </li>
                            <li>
                                <a href="/e/{{$enterprise->namespace}}/user/create">Create</a>
                            </li>
                        </ul>
                    </li>
                    <li class="disabled">
                        <a href="#">External organisations</a>
                    </li>
                    <li class="disabled">
                        <a href="#">Positions</a>
                    </li>
                    <li class="disabled">
                        <a href="#">Settings</a>
                    </li>
                @endif
            </ul>
        </div>
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

        <div class="container-fluid">

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

</body>

</html>
