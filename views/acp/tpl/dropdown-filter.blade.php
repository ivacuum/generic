<?php
$current = array_keys($values, request($field))[0];
$current = mb_strtolower(mb_substr($current, 0, 1)).mb_substr($current, 1);
?>
<div class="dropdown tw-my-1 tw-mr-2 {{ $class ?? '' }}">
  <a class="btn btn-default dropdown-toggle" href="#" data-toggle="dropdown">
    <span class="text-muted">
      @svg (filter)
    </span>
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
