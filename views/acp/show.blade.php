@extends("$tpl.base")

@section('content')
@if (Auth::user()->isRoot())
  <div class="bg-light border mt-3 py-1 px-2">
    <pre class="d-inline-block text-break-word mb-0 mw-100">{{ $model->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
  </div>
@endif
@endsection
