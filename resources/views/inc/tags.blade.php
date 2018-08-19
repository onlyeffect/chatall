<h3>Tags</h3>
<ul class="list-unstyled">
  @foreach($tags as $tag)
    @if($tag->posts_count > 0)
      <li style="border-bottom:1px solid #ddd"><a href="{{ route('posts.index') }}?tag={{ $tag->name }}">#{{$tag->name}}</a> ({{ $tag->posts_count }})</li>
    @endif
  @endforeach
</ul>