@foreach ($values as $_value => $_title)
  <label class="flex items-center">
    <input
      class="form-radio mr-2"
      type="radio"
      name="{{ $name }}"
      value="{{ $_value }}"
      {{ $_value == old($name, $model->{$name}) ? 'checked' : '' }}
    >
    {{ $_title }}
  </label>
@endforeach
