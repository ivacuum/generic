<div class="form-group {{ $errors->has($name) ? 'has-error' : '' }}">
  <label class="col-md-3 control-label {{ $required ? 'required' : '' }}">{{ $label ?? ViewHelper::modelFieldTrans($entity, $name) }}:</label>
  <div class="col-md-6">
    @include("acp.tpl.input-{$type}")
    @if ($help)
      <span class="help-block">{{ $help }}</span>
    @endif
    @if ($errors->has($name))
      <span class="help-block">{{ $errors->first($name) }}</span>
    @endif
  </div>
</div>
