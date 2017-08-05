<div class="form-select">
  <select {{ $required ? 'required' : '' }} class="form-control {{ implode(' ', $classes) }}" name="{{ $name }}">
    <option value=""></option>
    @foreach ($values as $_value => $_title)
      <option value="{{ $_value }}" {{ $_value == old($name, $model->{$name} ?? Request::input($name)) ? 'selected' : '' }}>{{ $_title }}</option>
    @endforeach
  </select>
</div>
