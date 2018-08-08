@extends('layouts.app')

@section('content')
    <h1>Most Active Users</h1><br>
    <table class="table table-striped table-bordered">
    <thead>
        <th>#</th>
        <th>Username</th>
        <th>Contributed to</th>
    </thead>
    <tbody>
    @for($i = 0; $i < count($users); $i++)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $users[$i]->name }}</td>
            <td>
                @foreach($users[$i]->getTags() as $tag)
                    <a href="{{ route('posts.index') }}?tag={{ $tag->name }}">#{{$tag->name}}</a>
                @endforeach
            </td>
        </tr>
    @endfor
    </tbody>
    </table>
@endsection