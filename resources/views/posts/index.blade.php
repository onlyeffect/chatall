@extends('layouts.app')

@section('content')
    @if(count($posts) > 0)
        @foreach($posts as $post)
            <div class="well"> 
                <div class="row">
                    <div class="col-md-4 col-sm-4">
                        <img style="width:100%" src="/storage/post_images/{{ $post->post_image }}" alt="Post Image">
                    </div>
                    <div class="col-md-8 col-sm-8">
                        <h2><a class="text-justify" href="{{ route('posts.show', $post->id) }}">{{$post->title}}</a></h2>
                        <p>Created {{\Carbon\Carbon::parse($post->created_at)->diffForHumans()}} by <strong>{{$post->user['name']}}</strong></p>
                        <h4>Comments: <a href="{{ route('posts.show', $post->id) }}#comments">{{count($post->comments)}}</a></h4>
                        <h4>Tags: 
                            @foreach($post->tags as $tag)
                                <a href="?tag={{ $tag->name }}">{{$tag->name}}</a>
                            @endforeach
                        </h4>
                    </div>
                </div>
            </div>
        @endforeach
        {{$posts->links()}}
    @else
        <div class="well">
            <p>No posts found.</p>
        </div>
    @endif
@endsection