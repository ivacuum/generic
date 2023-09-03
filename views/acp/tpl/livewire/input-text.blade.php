<input
  {{ $required ? 'required' : '' }}
  type="{{ $type }}"
  class="form-input {{ implode(' ', $classes) }}"
  @if($live)
    wire:model.live="{{ $name }}"
  @else
    wire:model="{{ $name }}"
  @endif
  placeholder="{{ $placeholder }}"
  id="{{ $entity }}_{{ $name }}"
>
