<textarea
  {{ $required ? 'required' : '' }}
  class="form-control {{ !$isMobile ? 'textarea-autosized js-autosize-textarea' : '' }} {{ $errors->has($name) ? 'is-invalid' : '' }} {{ implode(' ', $classes) }}"
  name="{{ $name }}"
  rows="{{ !$isMobile ? 2 : 6 }}"
  placeholder="{{ $placeholder }}"
  id="{{ $entity }}_{{ $name }}"
>{{ old($name, $model->{$name}) }}</textarea>
