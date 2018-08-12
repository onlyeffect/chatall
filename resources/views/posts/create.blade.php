@extends('layouts.app')

@section('styles')
    <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <div class="col-sm-10">
        <h1>Create Post</h1>
        <form action="{{route('posts.store')}}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" class="form-control" placeholder="Title" required>
            </div>

            <div class="form-group">
                <label for="body">Post body</label>
                <textarea name="body" rows="8"></textarea>
            </div>

            <div class="form-group">
                <select name="tags[]" id="tagList" multiple="multiple" class="form-control" required>
                    @foreach($tags as $tag)
                        <option value="{{$tag->name}}">{{$tag->name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <input type="file" name="post_image" accept="image/*">
            </div>

            <input type="submit" class="btn btn-primary" value="Create">

        </form>
    </div>

    <div class="col-sm-2">@include('inc.tags')</div>

@endsection

@section('scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
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