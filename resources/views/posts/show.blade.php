@extends('layouts.app')

@section('content')
    <div class="form-group">
        <p><a href="/" class="btn btn-default">Go back</a></p>
        <h1>{{$post->title}}</h1>
        <h4>Tags: 
            @foreach($post->tags as $tag)
                <a href="{{ route('posts.index') }}?tag={{ $tag->name }}">#{{$tag->name}}</a>
            @endforeach
        </h4>
        <img style="width:60%;" src="/storage/post_images/{{$post->post_image}}" alt="Post Image">
        <h4>{!! $post->body !!}</h4>
        <hr>
        <small>Created {{\Carbon\Carbon::parse($post->created_at)->diffForHumans()}} by {{$post->user['name']}}</small>
    </div>
    @auth
        @if($post->user_id === auth()->user()->id)
            <div class="form-group">
                <form action="{{route('posts.destroy', $post->id)}}" method="POST">
                    <a href="{{route('posts.edit', $post->id)}}" class="btn btn-primary">Edit</a>
                    <input type="submit" class="btn btn-danger" value="Delete">
                    {{method_field('DELETE')}}
                    {{csrf_field()}}
                </form>
            </div>
        @endif
        <form action="{{route('posts.addComment', $post->id)}}" method="POST">
            {{csrf_field()}}
            <div class="form-group">
                <textarea name="body" required style="resize:none" rows="6" placeholder="Type your comment here :)" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <input type="submit" value="Add Comment" class="btn btn-primary">
            </div>
        </form>
    @endauth
    <h3>Comments:</h3>
    <div class="well" id="comments">
        @if(count($comments) > 0)
            @foreach($comments as $comment)
                <h4>{{$comment->body}}</h4>
                <p>{{\Carbon\Carbon::parse($comment->created_at)->diffForHumans()}} by <strong>{{$comment->user->name}}</strong></p>
                <hr>
            @endforeach
        @else
            <p>No comments yet. You can be the first!</p>
        @endif
    </div>
@endsection