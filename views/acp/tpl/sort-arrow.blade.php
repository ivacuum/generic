@if ($sort_key === $key)
  @if ($sort_dir === 'desc')
    @svg (angle-down)
  @else
    @svg (angle-up)
  @endif
@endif
