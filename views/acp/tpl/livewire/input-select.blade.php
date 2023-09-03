<select
  {{ $required ? 'required' : '' }}
  class="form-input {{ implode(' ', $classes) }}"
  @if($live)
    wire:model.live="{{ $name }}"
  @else
    wire:model="{{ $name }}"
  @endif
  id="{{ $entity }}_{{ $name }}"
>
  <option value=""></option>
  @foreach ($values as $_value => $_title)
    <option value="{{ $_value }}">{{ $_title }}</option>
  @endforeach
</select>
