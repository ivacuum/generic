<textarea
  {{ $required ? 'required' : '' }}
  class="form-input {{ implode(' ', $classes) }}"
  @if($live)
    wire:model.live="{{ $name }}"
  @else
    wire:model="{{ $name }}"
  @endif
  rows="4"
  placeholder="{{ $placeholder }}"
  id="{{ $entity }}_{{ $name }}"
></textarea>
