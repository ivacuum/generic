@extends('acp.layout')

@section('model_menu')
@if ($model->user_id > 0)
  @component('tpl.list-group-item', ['href' => path([App\Http\Controllers\Acp\Users::class, 'show'], $model->user_id)])
    {{ trans("$tpl.user") }}
    @if (null !== $model->user)
      <div class="text-xs text-muted">{{ $model->user->email }}</div>
    @endif
  @endcomponent
@endif
@endsection
