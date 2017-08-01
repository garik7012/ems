@extends('layouts.main')
@section('page_name', 'Create department')
@section('content')
    <div class="container">
        <h2>Create department form. Enterprise: {{$enterprise->name}}</h2>

        <ul id="accordion">
            <li class="panel">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">1. What is HTML?</a>
                <div id="collapseOne" class="panel-collapse collapse in">
                    <div class="panel-body">
                        <p>HTML stands for HyperText Markup Language. HTML is the main markup language for describing the structure of Web pages. <a href="https://www.tutorialrepublic.com/html-tutorial/" target="_blank">Learn more.</a></p>
                    </div>
                </div>
            </li>
            <li >
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">3. What is CSS?</a>
            </li>
            <li class="panel">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">2. What is Bootstrap?</a>
                <div id="collapseTwo" class="panel-collapse collapse">
                    <div class="panel-body">
                        <p>Bootstrap is a powerful front-end framework for faster and easier web development. It is a collection of CSS and HTML conventions. <a href="https://www.tutorialrepublic.com/twitter-bootstrap-tutorial/" target="_blank">Learn more.</a></p>
                    </div>
                </div>
            </li>

        </ul>
    </div>
@endsection