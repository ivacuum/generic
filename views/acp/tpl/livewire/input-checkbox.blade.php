@foreach ($values as $_value => $_title)
  <label class="flex items-center">
    <input
      class="border-gray-300 mr-2"
      type="checkbox"
      wire:model="{{ $name }}"
      value="{{ $_value }}"
    >
    {{ $_title }}
  </label>
@endforeach
