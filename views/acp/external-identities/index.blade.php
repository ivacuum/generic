@extends('acp.list')

@section('heading-after-search')
@include('acp.tpl.dropdown-filter', [
  'field' => 'provider',
  'values' => [
    'Все' => null,
    '---' => null,
    'ВК' => 'vk',
    'Гитхаб' => 'github',
    'Гугл' => 'google',
    'Одноклассники' => 'odnoklassniki',
    'Твиттер' => 'twitter',
    'Фэйсбук' => 'facebook',
    'Яндекс' => 'yandex',
  ]
])
@endsection

@section('content-list')
<table class="table-stats table-adaptive">
  <thead>
  <tr>
    <th class="md:tw-text-right">ID</th>
    <th></th>
    <th>Пользователь</th>
    <th>Вход</th>
  </tr>
  </thead>
  <tbody>
  @foreach ($models as $model)
    <tr>
      <td class="md:tw-text-right">
        <a href="{{ path("$self@show", $model) }}">
          {{ $model->id }}
        </a>
      </td>
      <td class="bg-{{ $model->provider }}">
        <a class="tw-text-white hover:tw-text-gray-400" href="{{ $model->externalLink() }}">
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
