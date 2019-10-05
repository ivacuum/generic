@foreach ($values as $_value => $_title)
  <label class="flex items-center">
    <input
      class="mr-2 {{ $errors->has($name) ? 'is-invalid' : '' }}"
      type="radio"
      name="{{ $name }}"
      value="{{ $_value }}"
      {{ $_value == old($name, $model->{$name}) ? 'checked' : '' }}
    >
    {{ $_title }}
  </label>
@endforeach
