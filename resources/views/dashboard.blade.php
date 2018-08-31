@extends('layouts.app')

@section('content')
<div class="col-md-8 col-md-offset-2">
    <div class="panel panel-default">
        <div class="panel-heading">Dashboard</div>

        <div class="panel-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <a href="{{route('posts.create')}}" class="btn btn-primary">Create Post</a>

            <h3>Your Posts</h3>
            @if(count($userPosts) > 0)
                @foreach($userPosts as $userPost)
                    <hr>
                    <h4><a href="{{route('posts.show', $userPost->id)}}">{{$userPost->title}}</a></h4>
                    <div>Created {{\Carbon\Carbon::parse($userPost->created_at)->diffForHumans()}}</div>
                @endforeach
            @else
                <span>You have no posts</span>
            @endif
        </div>
    </div>
</div>
@endsection
