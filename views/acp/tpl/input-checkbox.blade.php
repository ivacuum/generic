<div class="checkbox">
  @if (!is_null($default))
    <input type="hidden" name="{{ $name }}" value="{{ $default }}">
  @endif
  @foreach ($values as $_value => $_title)
    <label>
      <input type="checkbox" name="{{ $name }}" value="{{ $_value }}" {{ $_value == old($name, $model->{$name}) ? 'checked' : '' }}>
      <span>{{ $_title }}</span>
    </label>
  @endforeach
</div>
