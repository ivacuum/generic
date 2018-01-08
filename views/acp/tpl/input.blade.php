<div class="form-group form-row align-items-center">
  <label class="col-sm-4 col-md-3 font-weight-bold lh13 text-sm-right {{ !in_array($type, ['checkbox', 'radio']) ? 'mb-sm-0' : '' }} {{ $required ? 'input-required' : '' }}">{{ $label ?? ViewHelper::modelFieldTrans($entity, $name) }}</label>
  <div class="col-sm-8 col-md-6">
    @include("acp.tpl.input-{$type}")
    @if ($help)
      <div class="f14 form-text text-muted">{{ $help }}</div>
    @endif
    @if ($errors->has($name))
      <div class="invalid-feedback">{{ $errors->first($name) }}</div>
    @endif
  </div>
</div>
