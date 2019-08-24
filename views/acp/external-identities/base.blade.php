@extends('acp.layout')

@section('model_menu')
@if ($model->user_id > 0)
  <a class="list-group-item list-group-item-action" href="{{ path('Acp\Users@show', $model->user_id) }}">
    {{ trans("$tpl.user") }}
    @if (null !== $model->user)
      <div class="tw-text-xs text-muted">{{ $model->user->email }}</div>
    @endif
  </a>
@endif
@endsection
