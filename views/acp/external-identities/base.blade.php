@extends('acp.layout')

@section('model_menu')
@if ($model->user_id > 0)
  <a class="list-group-item list-group-item-action" href="{{ path('Acp\Users@show', $model->user_id) }}">
    {{ trans("$tpl.user") }}
    @if (!is_null($model->user))
      <div class="small text-muted">{{ $model->user->email }}</div>
    @endif
  </a>
@endif
@endsection
