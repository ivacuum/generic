@foreach ($values as $_value => $_title)
  <label class="form-check">
    <input class="form-check-input {{ $errors->has($name) ? 'is-invalid' : '' }}" type="radio" name="{{ $name }}" value="{{ $_value }}" {{ $_value == old($name, $model->{$name}) ? 'checked' : '' }}>
    <span class="form-check-label">{{ $_title }}</span>
  </label>
@endforeach
