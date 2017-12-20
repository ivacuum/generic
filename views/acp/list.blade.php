@extends('acp.base')

@section('content')
<div class="d-flex align-items-center flex-wrap mb-2 mt--2">
  <h3 class="mt-0 mb-1 mr-3">
    {{ trans("$tpl.index") }}
    @if ($models instanceof Illuminate\Support\Collection)
      <small>{{ ViewHelper::number(sizeof($models)) }}</small>
    @else
      <small>{{ ViewHelper::number($models->total()) }}</small>
    @endif
  </h3>
  @yield('heading-after-title')
  @can('create', $model)
    @include('acp.tpl.create')
  @endcan
  @if (!empty($search_form))
    <form class="mr-2">
      <input name="q" class="form-control" placeholder="{{ ViewHelper::modelFieldTrans($model_tpl, 'q_placeholder') }}" value="{{ $q ?? '' }}">
    </form>
  @endif
  @yield('heading-after-search')
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
