@foreach ($values as $_value => $_title)
  <label class="flex gap-2 items-center">
    <input
      class="border-gray-300"
      type="checkbox"
      wire:model="{{ $name }}"
      value="{{ $_value }}"
    >
    {{ $_title }}
  </label>
@endforeach
