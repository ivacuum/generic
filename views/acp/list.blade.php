@extends('acp.base')

@section('content')
<div class="flex items-center flex-wrap mb-2 -mt-2">
  <h3 class="mb-1 mr-4">
    {{ trans("$tpl.index") }}
    <span class="text-base text-muted whitespace-no-wrap">
      {{ $models instanceof Illuminate\Support\Collection
          ? ViewHelper::number(sizeof($models))
          : ViewHelper::number($models->total())
      }}
    </span>
  </h3>
  @yield('heading-after-title')
  @can('create', $model)
    @include('acp.tpl.create-button')
  @endcan
  @if (!empty($searchForm))
    <form class="my-1 mr-2">
      <input name="q" class="form-control" placeholder="{{ ViewHelper::modelFieldTrans($modelTpl, 'q_placeholder') }}" value="{{ $q ?? '' }}" autocapitalize="none">
    </form>
  @endif
  @yield('heading-after-search')
</div>

@yield('toolbar')

@if (!empty($filters = Request::except(['filter', 'page', 'sd', 'sk', '_pjax'])))
  <div class="my-2">
    <a class="btn btn-default my-1" href="{{ path([$controller, 'index']) }}">
      {{ trans('acp.reset_filters') }}
    </a>
    @foreach ($filters as $key => $value)
      <a class="btn btn-default my-1" href="{{ fullUrl(array_merge($filters, ['page' => null, $key => null, '_pjax' => null])) }}">
        {{ $key }}: {{ $value }}
        <span class="text-red-600">
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
