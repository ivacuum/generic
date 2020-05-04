<input
  {{ $required ? 'required' : '' }}
  type="{{ $type }}"
  class="form-input {{ implode(' ', $classes) }}"
  name="{{ $name }}"
  value="{{ old($name, $model->{$name} ?? $default) }}"
  placeholder="{{ $placeholder }}"
  id="{{ $entity }}_{{ $name }}"
>
