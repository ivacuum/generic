@extends("$tpl.base")

@section('content')
@if (Auth::user()->isRoot())
  <details class="tw-mt-4">
    <summary class="tw-outline-none">JSON</summary>
    <div class="tw-bg-light border tw-mt-1 tw-py-1 tw-px-2 tw-rounded">
      <pre class="tw-inline-block tw-break-words tw-mb-0 tw-max-w-full">{{ $model->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
    </div>
  </details>
@endif
@endsection
