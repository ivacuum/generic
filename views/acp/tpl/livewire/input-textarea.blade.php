<textarea
  {{ $required ? 'required' : '' }}
  class="form-input {{ implode(' ', $classes) }}"
  @if ($lazy)
    wire:model.lazy="{{ $name }}"
  @else
    wire:model="{{ $name }}"
  @endif
  rows="4"
  placeholder="{{ $placeholder }}"
  id="{{ $entity }}_{{ $name }}"
></textarea>
