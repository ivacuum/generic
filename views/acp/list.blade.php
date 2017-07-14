@extends('acp.base')

@section('content')
<div class="heading-menu">
  <h3 class="mt-0">
    {{ trans("$tpl.index") }}
    @if ($models instanceof Illuminate\Support\Collection)
      <small>{{ ViewHelper::number(sizeof($models)) }}</small>
    @else
      <small>{{ ViewHelper::number($models->total()) }}</small>
    @endif
    @can('create', $model)
      @include('acp.tpl.create')
    @endcan
    @if (!empty($search_form))
      <form class="heading-menu-search-form">
        <input type="text" name="q" class="form-control" placeholder="Поиск..." value="{{ $q ?? '' }}">
      </form>
    @endif
  </h3>
</div>

@yield('toolbar')

@if (!empty($filters = Request::except(['filter', 'page', 'sd', 'sk', '_pjax'])))
  <div class="my-3">
    <a class="btn btn-default" href="{{ path("$self@index") }}">
      {{ trans('acp.reset_filters') }}
      <span class="text-danger">
        @svg (times)
      </span>
    </a>
    @foreach ($filters as $key => $value)
      <a class="btn btn-default" href="{{ fullUrl(array_merge($filters, ['page' => null, $key => null, '_pjax' => null])) }}">
        {{ $key }}: {{ $value }}
        <span class="text-danger">
          @svg (times)
        </span>
      </a>
    @endforeach
  </div>
@endif

@if (sizeof($models))
  @yield('content-list')
@else
  @yield('content-list-empty')
@endif

@if ($models instanceof Illuminate\Contracts\Pagination\Paginator)
  @include('tpl.paginator', ['paginator' => $models])
@endif
@endsection
