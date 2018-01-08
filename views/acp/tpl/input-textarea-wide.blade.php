<div class="form-group">
  <label class="font-weight-bold {{ $required ? 'input-required' : '' }}">{{ $label ?? ViewHelper::modelFieldTrans($entity, $name) }}</label>
  <textarea {{ $required ? 'required' : '' }} class="form-control textarea-autosized textarea-borderless-focus js-autosize-textarea {{ implode(' ', $classes) }}" name="{{ $name }}" rows="2" placeholder="{{ $placeholder }}">{{ old($name, $model->{$name}) }}</textarea>
  @if ($help)
    <div class="f14 form-text text-muted">{{ $help }}</div>
  @endif
  @if ($errors->has($name))
    <div class="invalid-feedback">{{ $errors->first($name) }}</div>
  @endif
</div>
