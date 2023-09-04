@foreach ($values as $_value => $_title)
  <label class="flex gap-2 items-center">
    <input
      class="border-gray-300"
      type="radio"
      @if($live)
        wire:model.live="{{ $name }}"
      @elseif($blur)
        wire:model.blur="{{ $name }}"
      @else
        wire:model="{{ $name }}"
      @endif
      value="{{ $_value }}"
    >
    {{ $_title }}
  </label>
@endforeach
