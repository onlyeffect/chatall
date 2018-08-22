@extends('layouts.app')

@section('content')
<div class="col-sm-10">
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
                        <h4>Views: {{ $post->views }}</h4>
                        <h4>Tags: 
                            @foreach($post->tags as $tag)
                                <a href="{{ route('posts.index') }}?tag={{ $tag->name }}">#{{$tag->name}}</a>
                            @endforeach
                        </h4>
                        @auth
                            @if(auth()->user()->isAdmin)
                                <div class="form-group">
                                    <form action="{{route('posts.destroy', $post->id)}}" method="POST">
                                        <a href="{{route('posts.edit', $post->id)}}" class="btn btn-primary">Edit</a>
                                        <input type="submit" class="btn btn-danger" value="Delete">
                                        {{method_field('DELETE')}}
                                        {{csrf_field()}}
                                    </form>
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        @endforeach
        {{$posts->appends(['tag' => $tagName])->links()}}
    @else
        <div class="well">
            <p>No posts found.</p>
        </div>
    @endif
</div>

<div class="col-sm-2">@include('inc.popular_posts')</div>
<div class="col-sm-2">@include('inc.tags')</div>

@endsection