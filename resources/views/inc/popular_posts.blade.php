<h3>Popular posts</h3>
<ul class="list-unstyled">
  @foreach($popularPosts as $post)
      <li><a href="{{ route('posts.show', $post->id) }}">{{$post->title}}</a> ({{ $post->views }} views)</li>
  @endforeach
</ul>