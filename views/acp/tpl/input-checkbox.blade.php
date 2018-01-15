@if (!is_null($default))
  <input type="hidden" name="{{ $name }}" value="{{ $default }}">
@endif
@foreach ($values as $_value => $_title)
  <label class="form-check">
    <input class="form-check-input {{ $errors->has($name) ? 'is-invalid' : '' }}" type="checkbox" name="{{ $name }}" value="{{ $_value }}" {{ $_value == old($name, $model->{$name}) ? 'checked' : '' }}>
    <span class="form-check-label">{{ $_title }}</span>
  </label>
@endforeach
