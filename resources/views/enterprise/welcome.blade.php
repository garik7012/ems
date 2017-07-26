@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h2>List of enterprises</h2>
                @forelse($enterprises as $enterprise)
                    <a href="e/{{$enterprise->namespace}}">
                        <h3>{{$enterprise->name}}</h3>
                    </a>
                @empty
                    <h2>there is no enterprise</h2>
                @endforelse

            </div>
        </div>
    </div>
@endsection