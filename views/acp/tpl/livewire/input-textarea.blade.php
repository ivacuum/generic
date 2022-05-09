<textarea
  {{ $required ? 'required' : '' }}
  class="form-input {{ !$isMobile ? 'resize-none js-autosize-textarea' : '' }} {{ implode(' ', $classes) }}"
  @if ($lazy)
    wire:model.lazy="{{ $name }}"
  @else
    wire:model="{{ $name }}"
  @endif
  rows="{{ !$isMobile ? 2 : 6 }}"
  placeholder="{{ $placeholder }}"
  id="{{ $entity }}_{{ $name }}"
></textarea>
