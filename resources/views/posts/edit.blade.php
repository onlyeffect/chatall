@extends('layouts.app')

@section('styles')
    <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="col-sm-10">
    <h1>Update Post</h1>
    <form action="{{route('posts.update', $post->id)}}" method="POST" enctype="multipart/form-data">
        {{ method_field('PUT') }}
        {{ csrf_field() }}

        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control" placeholder="Title" value="{{$post->title}}">
        </div>

        <div class="form-group">
            <label for="body">Post body</label>
            <textarea name="body" rows="8">{!! $post->body !!}</textarea>
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
    </form>
</div>

<div class="col-sm-2">@include('inc.tags')</div>

@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="{{ asset('tinymce/tinymce.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            tinymce.init({
                selector:'textarea',
                plugins: "paste",
                paste_as_text: true,
                menubar: false,
                toolbar: 'undo, redo | bold, italic, underline, strikethrough | subscript, superscript | bullist numlist',
            });
            $('#tagList').select2({
                placeholder: 'Select a tag',
                tags: true,
                width: '100%',
            });
        });
    </script>
@endsection