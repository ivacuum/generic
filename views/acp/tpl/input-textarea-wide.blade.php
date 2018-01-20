<div class="form-group">
  <label class="{{ $required ? 'input-required' : '' }}">{{ $label ?? ViewHelper::modelFieldTrans($entity, $name) }}</label>
  <textarea {{ $required ? 'required' : '' }} class="form-control {{ !$is_mobile ? 'textarea-autosized textarea-borderless-focus js-autosize-textarea' : '' }} {{ $errors->has($name) ? 'is-invalid' : '' }} {{ implode(' ', $classes) }}" name="{{ $name }}" rows="{{ !$is_mobile ? 2 : 6 }}" placeholder="{{ $placeholder }}" id="{{ $entity }}_{{ $name }}">{{ old($name, $model->{$name}) }}</textarea>
  @if ($errors->has($name))
    <div class="invalid-feedback">{{ $errors->first($name) }}</div>
  @endif
  @if ($help)
    <div class="form-help">{{ $help }}</div>
  @endif
</div>
