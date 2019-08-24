@if (null !== $default)
  <input type="hidden" name="{{ $name }}" value="{{ $default }}">
@endif
@foreach ($values as $_value => $_title)
  <label class="tw-flex tw-items-center tw-font-normal">
    <input
      class="tw-mr-2 {{ $errors->has($name) ? 'is-invalid' : '' }}"
      type="checkbox"
      name="{{ $name }}"
      value="{{ $_value }}"
      {{ $_value == old($name, $model->{$name}) ? 'checked' : '' }}
    >
    {{ $_title }}
  </label>
@endforeach
