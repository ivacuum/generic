@foreach ($values as $_value => $_title)
  <label class="flex items-center">
    <input
      class="border-gray-300 mr-2"
      type="radio"
      name="{{ $name }}"
      value="{{ $_value }}"
      {{ $_value == old($name, $model->{$name} instanceof JsonSerializable ? $model->{$name}->jsonSerialize() : $model->{$name}) ? 'checked' : '' }}
    >
    {{ $_title }}
  </label>
@endforeach
