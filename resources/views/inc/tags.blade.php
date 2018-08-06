<h3>Tags</h3>
<ul class="list-unstyled">
  @foreach($tags as $tag)
    @if($tagsAmount = $tag->posts->count())
      <li><a href="{{ route('posts.index') }}?tag={{ $tag->name }}">#{{$tag->name}}</a> ({{ $tagsAmount }})</li>
    @endif
  @endforeach
</ul>