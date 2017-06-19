<input {{ $required ? 'required' : '' }} type="{{ $type }}" class="form-control" name="{{ $name }}" value="{{ old($name, $model->{$name}) }}">
