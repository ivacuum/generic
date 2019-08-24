@extends('acp.base')

@section('content')
<h2 class="tw-break-words">
  {{ $event }}
  <span class="tw-text-base text-muted tw-whitespace-no-wrap">{{ ViewHelper::number($metrics->sum()) }}</span>
</h2>
<table class="table-stats table-adaptive">
  <thead>
    <tr>
      <th>Дата</th>
      <th class="md:tw-text-right tw-whitespace-no-wrap">Кол-во</th>
    </tr>
  </thead>
  <tbody>
    @for ($date = $last_day; $first_day->lte($date); $date->subDay())
      @php ($day = $date->toDateString())
      <tr>
        <td>{{ $day }}</td>
        <td class="md:tw-text-right tw-whitespace-no-wrap">{{ isset($metrics[$day]) ? ViewHelper::number($metrics[$day]) : '' }}</td>
      </tr>
    @endfor
  </tbody>
</table>
@endsection
