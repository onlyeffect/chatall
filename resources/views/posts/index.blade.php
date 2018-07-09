@extends('layouts.app')

@section('content')
    <!-- Carousel
    ================================================== -->
    <div id="myCarousel" class="carousel slide" data-ride="carousel">
      <!-- Indicators -->
      <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#myCarousel" data-slide-to="1"></li>
      </ol>
      <div class="carousel-inner" role="listbox">
        <div class="item active">
          <img class="first-slide" src="/storage/logo1.png" alt="First slide">
          <div class="container">
            <div class="carousel-caption">
            </div>
          </div>
        </div>
        <div class="item">
          <img class="second-slide" src="/storage/logo2.png" alt="Second slide">
          <div class="container">
            <div class="carousel-caption">
              <p><a class="btn btn-lg btn-primary" href="#" role="button">Pinus</a></p>
            </div>
          </div>
        </div>
      </div>
      <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div>
    <br>
    @if(count($posts) > 0)
        @foreach($posts as $post)
            <div class="well"> 
                <div class="row">
                    <div class="col-md-4 col-sm-4">
                        <img style="width:100%" src="/storage/post_images/{{$post->post_image}}" alt="Post Image">
                    </div>
                    <div class="col-md-8 col-sm-8">
                        <h2><a class="text-justify" href="{{route('posts.show', $post->id)}}">{{$post->title}}</a></h2>
                        <p>Created {{\Carbon\Carbon::parse($post->created_at)->diffForHumans()}} by <strong>{{$post->user['name']}}</strong></p>
                        <h4>Comments: <a href="{{route('posts.show', $post->id)}}#comments">{{count($post->comments)}}</a></h4>
                        <h4>Tags: 
                            @foreach($post->tags as $tag)
                                <a href="#">{{$tag->name}}</a>
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