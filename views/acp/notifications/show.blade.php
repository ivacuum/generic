@extends('acp.show')

@section('content')
<div class="tw-bg-light border tw-mt-4 tw-py-1 tw-px-2">
  <pre class="tw-inline-block tw-break-words tw-mb-0 tw-max-w-full">{{ json_encode(json_decode($model->data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
</div>
@parent
@endsection
