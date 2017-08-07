<form class="form-inline js-batch-form"
      data-url="{{ path("$self@batch") }}"
      data-selector=".models-checkbox">
  <div class="form-group">
    <div class="form-select d-inline-block mr-1">
      <select class="form-control" name="action">
        <option value="">Выберите действие...</option>
        @foreach ($actions as $value => $title)
          <option value="{{ $value }}">{{ $title }}</option>
        @endforeach
      </select>
    </div>
  </div>
  <button class="btn btn-default">Выполнить</button>
</form>
