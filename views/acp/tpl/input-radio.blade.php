@foreach ($values as $_value => $_title)
  <label class="flex gap-2 items-center">
    <input
      class="border-gray-300"
      type="radio"
      name="{{ $name }}"
      value="{{ $_value }}"
      {{ $_value == old($name, $model->{$name} instanceof BackedEnum ? $model->{$name}->value : $model->{$name}) ? 'checked' : '' }}
    >
    {{ $_title }}
  </label>
@endforeach
