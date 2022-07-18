<form
  class="flex flex-wrap gap-1 js-batch-form"
  data-url="{{ path([$controller, 'batch']) }}"
  data-selector=".models-checkbox"
>
  <div>
    <select class="form-input" name="action">
      <option value="">Выберите действие...</option>
      @foreach ($actions as $value => $title)
        <option value="{{ $value }}">{{ $title }}</option>
      @endforeach
    </select>
  </div>
  <button class="btn btn-default">Выполнить</button>
</form>
