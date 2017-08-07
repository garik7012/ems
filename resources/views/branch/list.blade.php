@extends('layouts.main')
@section('page_name', 'List of branches')
@section('content')
    <div class="col-lg-12">
        <h2>Enterprise's branch list</h2>
        <div class="table-responsive">
            @if(count($branches))
            <table class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Country</th>
                    <th>City</th>
                    <th>Address 1</th>
                    <th>Address 2</th>
                    <th>Main</th>
                </tr>
                </thead>
                <tbody>
                @foreach($branches as $branch)
                    <tr>
                        <td>{{$branch->id}}</td>
                        <td>{{$branch->name}}</td>
                        <td>{{$branch->country}}</td>
                        <td>{{$branch->city}}</td>
                        <td>{{$branch->address_1}}</td>
                        <td>{{$branch->address_2}}</td>
                        <td><span class="fa {{$branch->is_main ? 'fa-check' : ''}}"></span></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @else
                <h2>There is no branches</h2>
            @endif
        </div>
    </div>
@endsection