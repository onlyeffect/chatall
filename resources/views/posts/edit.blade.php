@extends('layouts.app')

@section('content')
    <h1>Update Post</h1>
    <form action="{{route('posts.update', $post->id)}}" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control" placeholder="Title" value="{{$post->title}}">
        </div>
        <div class="form-group">
            <label for="body">Post body</label>
            <textarea name="body" id="body" rows="8" class="form-control" placeholder="Post body">{{$post->body}}</textarea>
        </div>
        <div class="form-group">
            Update the tags:
            <select id="tagList" name="tags[]" multiple="multiple" size="{{count($post->tags) + count($freeTags)}}" class="form-control">
                @foreach($post->tags as $tag)
                    <option value="{{ $tag->name }}" selected>{{ $tag->name }}</option>
                @endforeach

                @foreach($freeTags as $freeTag)
                    <option value="{{ $freeTag->name }}">{{ $freeTag->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
                <input type="file" name="post_image" accept="image/*">
            </div>
        <input type="submit" class="btn btn-primary" value="Update">
        {{ method_field('PUT') }}
        {{ csrf_field() }}
    </form>
@endsection

@section('scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-json/2.6.0/jquery.json.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tagList').select2({
                tags: true,
            });
        });
    </script>
@endsection