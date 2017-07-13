<a href="{{ UrlHelper::sort($key, $order ?? 'desc') }}">
  @if (isset($svg))
    @svg ($svg)
  @else
    {{ ViewHelper::modelFieldTrans($model_tpl, $key) }}
  @endif
  @if ($sort_key === $key)
    @if ($sort_dir === 'desc')
      @svg (angle-down)
    @else
      @svg (angle-up)
    @endif
  @endif
</a>
