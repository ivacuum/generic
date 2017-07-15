<div class="form-group mt-3 {{ $errors->has($name) ? 'has-error' : '' }}">
  <div class="col-xs-12">
    <label class="control-label mb-2 {{ $required ? 'required' : '' }}">
      {{ trans("model.{$entity}.{$name}") }}:
    </label>
    <textarea {{ $required ? 'required' : '' }} class="form-control textarea-autosized textarea-borderless-focus js-autosize-textarea" name="{{ $name }}" rows="2" placeholder="{{ $placeholder }}">{{ old($name, $model->{$name}) }}</textarea>
    @if ($help)
      <span class="help-block">{{ $help }}</span>
    @endif
    @if ($errors->has($name))
      <span class="help-block">{{ $errors->first($name) }}</span>
    @endif
  </div>
</div>
