@foreach ($values as $_value => $_title)
  <div class="form-check">
    <label>
      <input class="form-check-input" type="radio" name="{{ $name }}" value="{{ $_value }}" {{ $_value == old($name, $model->{$name}) ? 'checked' : '' }}>
      <span>{{ $_title }}</span>
    </label>
  </div>
@endforeach
