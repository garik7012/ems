@extends('layouts.main')
@section('content')
    <div class="container">
        <h2>Welcome to {{$enterprise->name}} enterprise</h2>
        <h3>our namespase is {{$enterprise->namespace}}</h3>
        <div class="row">
            @if(Auth::user()->is_superadmin)
                You are admin!
            @else
                <h4>Current user is {{Auth::user()->first_name}}</h4>
            @endif
        </div>
    </div>

@endsection