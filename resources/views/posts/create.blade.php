@extends('layouts.app')

@section('content')
    <h1>Create Post</h1>
    <form action="{{route('posts.store')}}" method="POST" enctype="multipart/form-data">

        {{ csrf_field() }}

        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control" placeholder="Title" required>
        </div>

        <div class="form-group">
            <label for="body">Post body</label>
            <textarea name="body" id="body" rows="8" class="form-control" placeholder="Post body" required></textarea>
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
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-json/2.6.0/jquery.json.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tagList').select2({
                placeholder: 'Select a tag',
                tags: true,
            });
        });
    </script>
@endsection