@extends('acp.show')

@section('content')
<div class="bg-light border mt-3 py-1 px-2">
  <pre class="d-inline-block text-break-word mb-0 mw-100">{{ json_encode(json_decode($model->data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
</div>
@parent
@endsection
