<input
  {{ $required ? 'required' : '' }}
  type="{{ $type }}"
  class="form-control {{ $errors->has($name) ? 'is-invalid' : '' }} {{ implode(' ', $classes) }}"
  name="{{ $name }}"
  value="{{ old($name, $model->{$name} ?? $default) }}"
  placeholder="{{ $placeholder }}"
  id="{{ $entity }}_{{ $name }}"
>
