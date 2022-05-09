<input
  {{ $required ? 'required' : '' }}
  type="{{ $type }}"
  class="form-input {{ implode(' ', $classes) }}"
  @if ($lazy)
    wire:model.lazy="{{ $name }}"
  @else
    wire:model="{{ $name }}"
  @endif
  placeholder="{{ $placeholder }}"
  id="{{ $entity }}_{{ $name }}"
>
