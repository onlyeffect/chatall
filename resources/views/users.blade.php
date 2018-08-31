@extends('layouts.app')

@section('content')
<div class="col-sm-10">
    <h1>Most Active Users</h1><br>
    <table class="table table-striped table-bordered">
    <thead>
        <th>#</th>
        <th>Username</th>
        <th>Contributed to</th>
    </thead>
    <tbody>
    @for($i = 0; $i < count($activeUsers); $i++)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $activeUsers[$i]->name }}</td>
            <td>
                @foreach($activeUsers[$i]->getTags() as $tag)
                    <a href="{{ route('posts.index') }}?tag={{ $tag->name }}">#{{$tag->name}}</a>
                @endforeach
            </td>
        </tr>
    @endfor
    </tbody>
    </table>
</div>
    
<div class="col-sm-2">@include('inc.popular_posts')</div>
<div class="col-sm-2">@include('inc.tags')</div>
@endsection