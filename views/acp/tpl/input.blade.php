<div class="tw-mb-4">
  <label class="{{ $required ? 'input-required' : '' }}">{{ $label ?? ViewHelper::modelFieldTrans($entity, $name) }}</label>
  @include("acp.tpl.input-{$type}")
  @if ($errors->has($name))
    <div class="invalid-feedback tw-block">{{ $errors->first($name) }}</div>
  @endif
  @if ($help)
    <div class="form-help">{{ $help }}</div>
  @endif
</div>
