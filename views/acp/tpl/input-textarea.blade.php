<textarea
  {{ $required ? 'required' : '' }}
  class="form-textarea {{ !$isMobile ? 'resize-none js-autosize-textarea' : '' }} {{ implode(' ', $classes) }}"
  name="{{ $name }}"
  rows="{{ !$isMobile ? 2 : 6 }}"
  placeholder="{{ $placeholder }}"
  id="{{ $entity }}_{{ $name }}"
>{{ old($name, $model->{$name}) }}</textarea>
