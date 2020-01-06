@extends('layout')

@section('content')
    <a href="{{ route('authors.create') }}" class="btn btn-success mb-2">Add New</a>
    <br>
    @if(session('success'))
        <div class="alert alert-success">
            <strong>{{session('success')}}</strong>
        </div>
    @endif
    <div class="row">
        <div class="col-12">
            <table class="table table-sm table-light table-bordered table-hover" id="list">
                <thead class="thead-dark">
                <tr>
                    <th colspan="3">Author</th>
                    <th colspan="2">Book</th>
                </tr>
                <tr>
                    <th>Name</th>
                    <th>Birth Date</th>
                    <th>Address</th>
                    <th>Title</th>
                    <th>Release Date</th>
                </tr>
                </thead>
                <tbody>
                @foreach($authorsAndBooks as $row)
                    <tr>
                        <td>{{ $row->name }}</td>
                        <td>{{ $row->birth_date }}</td>
                        <td>{{ $row->address }}</td>
                        <td>{{ $row->title }}</td>
                        <td>{{ $row->release_date }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {!! $authorsAndBooks->links() !!}
        </div>
    </div>
@endsection
