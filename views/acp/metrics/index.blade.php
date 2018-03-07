@extends('acp.base')

@section('content')
<h3>{{ trans("$tpl.index") }}</h3>
<table class="table-stats table-adaptive">
  <thead>
  <tr>
    <th>Событие</th>
    @foreach ($dates as $date => $true)
      <th class="text-md-right text-nowrap">{{ substr($date, 5) }}</th>
    @endforeach
  </tr>
  </thead>
  <tbody>
  @foreach ($events as $event)
    <tr>
      <td><a class="text-break-word" href="{{ path("$self@show", $event) }}">{{ $event }}</a></td>
      @foreach ($dates as $date => $true)
        <td class="text-md-right text-nowrap">{{ isset($metrics[$event][$date]) ? ViewHelper::number($metrics[$event][$date]) : '' }}</td>
      @endforeach
    </tr>
  @endforeach
  </tbody>
</table>
@endsection
