@extends('acp.base')

@section('content')
<h2 class="text-break-word">
  {{ $event }}
  <small class="text-muted">{{ ViewHelper::number($metrics->sum()) }}</small>
</h2>
<table class="table-stats table-adaptive">
  <thead>
    <tr>
      <th>Дата</th>
      <th class="text-md-right">Кол-во</th>
    </tr>
  </thead>
  <tbody>
    @for ($date = $last_day; $first_day->lte($date); $date->subDay())
      @php ($day = $date->toDateString())
      <tr>
        <td>{{ $day }}</td>
        <td class="text-md-right">{{ isset($metrics[$day]) ? ViewHelper::number($metrics[$day]) : '' }}</td>
      </tr>
    @endfor
  </tbody>
</table>
@endsection
