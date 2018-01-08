@extends('acp.list')

@section('content-list')
<table class="table-stats table-adaptive">
  <thead>
  <tr>
    <th class="text-md-right">ID</th>
    <th></th>
    <th>Пользователь</th>
    <th>Вход</th>
  </tr>
  </thead>
  <tbody>
  @foreach ($models as $model)
    <tr>
      <td class="text-md-right">
        <a href="{{ path("$self@show", $model) }}">
          {{ $model->id }}
        </a>
      </td>
      <td class="bg-{{ $model->provider }}">
        <a href="{{ $model->externalLink() }}" style="color: white;">
          @svg ($model->provider)
        </a>
      </td>
      <td>
        @if ($model->user_id)
          <a href="{{ path('Acp\Users@show', $model->user_id) }}">{{ $model->user->email }}</a>
        @else
          {{ $model->email }}
        @endif
      </td>
      <td>{{ ViewHelper::dateShort($model->updated_at) }}</td>
    </tr>
  @endforeach
  </tbody>
</table>
@endsection
