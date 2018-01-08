<form class="form-inline js-batch-form"
      data-url="{{ path("$self@batch") }}"
      data-selector=".models-checkbox">
  <div class="form-group">
    <div class="d-inline-block mr-1">
      <select class="custom-select" name="action">
        <option value="">Выберите действие...</option>
        @foreach ($actions as $value => $title)
          <option value="{{ $value }}">{{ $title }}</option>
        @endforeach
      </select>
    </div>
  </div>
  <button class="btn btn-light border">Выполнить</button>
</form>
