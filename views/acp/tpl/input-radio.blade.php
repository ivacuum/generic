@foreach ($values as $_value => $_title)
  <div class="radio">
    <label>
      <input type="radio" name="{{ $name }}" value="{{ $_value }}" {{ $_value == old($name, $model->{$name}) ? 'checked' : '' }}>
      <span>{{ $_title }}</span>
    </label>
  </div>
@endforeach
