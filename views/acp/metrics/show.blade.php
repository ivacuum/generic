@extends('acp.base')

@section('content')
<h2 class="break-words">
  {{ $event }}
  <span class="text-base text-muted whitespace-no-wrap">{{ ViewHelper::number($metrics->sum()) }}</span>
</h2>
<table class="table-stats table-adaptive">
  <thead>
    <tr>
      <th>Дата</th>
      <th class="md:text-right whitespace-no-wrap">Кол-во</th>
    </tr>
  </thead>
  <tbody>
    <?php /** @var Illuminate\Support\Carbon $date */ ?>
    @for ($date = $lastDay; $firstDay->lte($date); $date->subDay())
      <?php $day = $date->toDateString() ?>
      <tr>
        <td>{{ $day }}</td>
        <td class="md:text-right whitespace-no-wrap">{{ isset($metrics[$day]) ? ViewHelper::number($metrics[$day]) : '' }}</td>
      </tr>
    @endfor
  </tbody>
</table>
@endsection
