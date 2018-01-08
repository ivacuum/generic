<?php
$current = array_keys($values, request($field))[0];
$current = mb_strtolower(mb_substr($current, 0, 1)).mb_substr($current, 1);
?>
<div class="dropdown py-1 mr-3 {{ $class ?? '' }}">
  <span class="text-muted">
    @svg (filter)
  </span>
  <a class="dropdown-toggle" href="#" data-toggle="dropdown">
    {{ $title ?? ViewHelper::modelFieldTrans($model_tpl, $field) }}: {{ $current }}
  </a>
  <div class="dropdown-menu">
    @foreach ($values as $name => $value)
      @if ($name === '---')
        <div class="dropdown-divider"></div>
      @else
        <a class="dropdown-item js-pjax" href="{{ UrlHelper::filter([$field => $value]) }}">
          {{ $name }}
        </a>
      @endif
    @endforeach
  </div>
</div>
