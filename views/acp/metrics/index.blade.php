@extends('acp.base')

@section('content')
<h3>@lang("$tpl.index")</h3>
<table class="table-stats table-adaptive">
  <thead>
  <tr>
    <th>Событие</th>
    @foreach ($dates as $date => $true)
      <th class="md:text-right whitespace-no-wrap">{{ substr($date, 5) }}</th>
    @endforeach
  </tr>
  </thead>
  <tbody>
  @foreach ($events as $event)
    <tr>
      <td><a class="break-words" href="{{ path([$controller, 'show'], $event) }}">{{ $event }}</a></td>
      @foreach ($dates as $date => $true)
        <td class="md:text-right whitespace-no-wrap">{{ isset($metrics[$event][$date]) ? ViewHelper::number($metrics[$event][$date]) : '' }}</td>
      @endforeach
    </tr>
  @endforeach
  </tbody>
</table>
@endsection
