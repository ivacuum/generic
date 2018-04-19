@extends("$tpl.base")

@section('content')
@if (Auth::user()->isRoot())
  <details class="mt-3">
    <summary class="outline-0">JSON</summary>
    <div class="bg-light border mt-1 py-1 px-2 rounded">
      <pre class="d-inline-block text-break-word mb-0 mw-100">{{ $model->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
    </div>
  </details>
@endif
@endsection
