<textarea
  {{ $required ? 'required' : '' }}
  class="form-control {{ !$is_mobile ? 'textarea-autosized js-autosize-textarea' : '' }} {{ $errors->has($name) ? 'is-invalid' : '' }} {{ implode(' ', $classes) }}"
  name="{{ $name }}"
  rows="{{ !$is_mobile ? 2 : 6 }}"
  placeholder="{{ $placeholder }}"
  id="{{ $entity }}_{{ $name }}"
>{{ old($name, $model->{$name}) }}</textarea>
