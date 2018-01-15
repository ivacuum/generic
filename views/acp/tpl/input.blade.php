<div class="form-group form-row">
  <label class="col-md-4 text-md-right {{ !in_array($type, ['checkbox', 'radio']) ? 'col-form-label' : '' }} {{ $required ? 'input-required' : '' }}">{{ $label ?? ViewHelper::modelFieldTrans($entity, $name) }}</label>
  <div class="col-md-6">
    @include("acp.tpl.input-{$type}")
    @if ($errors->has($name))
      <div class="invalid-feedback d-block">{{ $errors->first($name) }}</div>
    @endif
    @if ($help)
      <div class="form-help">{{ $help }}</div>
    @endif
  </div>
</div>
