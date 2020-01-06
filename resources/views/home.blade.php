@extends('layout')

@section('content')
    <div align="center">
        <h2>Welcome to Authors Society</h2>
        <p>On this beautiful site you can <strong>see the already saved authors</strong>
            after clicking on the "Show Data" button.<br>
            This is really cool, right?
        </p>
        <ul class="list-inline">
            <li class="list-inline-item">
                <a href="{{ route('authors.index') }}" class="btn btn-primary mb-2">Show Data</a>
            </li>
        </ul>
    </div>
@endsection
