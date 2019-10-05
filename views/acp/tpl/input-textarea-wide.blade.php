<div class="mb-4">
  <label class="font-bold {{ $required ? 'input-required' : '' }}">{{ $label ?? ViewHelper::modelFieldTrans($entity, $name) }}</label>
  <textarea
    {{ $required ? 'required' : '' }}
    class="form-control {{ !$isMobile ? 'textarea-autosized textarea-borderless-focus js-autosize-textarea' : '' }} {{ $errors->has($name) ? 'is-invalid' : '' }} {{ implode(' ', $classes) }}"
    name="{{ $name }}"
    rows="{{ !$isMobile ? 2 : 6 }}"
    placeholder="{{ $placeholder }}"
    id="{{ $entity }}_{{ $name }}"
  >{{ old($name, $model->{$name}) }}</textarea>
  @if ($errors->has($name))
    <div class="invalid-feedback">{{ $errors->first($name) }}</div>
  @endif
  @if ($help)
    <div class="form-help">{{ $help }}</div>
  @endif
</div>
