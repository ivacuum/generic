<?php
/** @var array $values */
/** @var string $field */
$current = array_keys($values, request($field))[0];
$current = mb_strtolower(mb_substr($current, 0, 1)).mb_substr($current, 1);
?>
<details class="relative details-reset details-overlay my-1 mr-2 {{ $class ?? '' }}">
  <summary class="btn btn-default">
    <span class="text-muted">
      @svg (filter)
    </span>
    {{ $title ?? ViewHelper::modelFieldTrans($modelTpl, $field) }}: {{ $current }}
    @svg (angle-down)
  </summary>
  <details-menu
    role="menu"
    class="absolute top-full left-0 z-50 py-2 bg-white mt-1 border border-gray-300 rounded shadow-md"
    style="min-width: 10rem;"
  >
    @foreach ($values as $name => $value)
      @if ($name === '---')
        <div class="h-0 my-2 overflow-hidden border-t border-gray-100"></div>
      @else
        <a
          class="dropdown-item-tw"
          href="{{ UrlHelper::filter([$field => $value]) }}"
          role="menuitem"
        >
          {{ $name }}
        </a>
      @endif
    @endforeach
  </details-menu>
</details>
