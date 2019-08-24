@extends('acp.base')

@section('content')
<div class="tw-flex tw-items-center tw-flex-wrap tw-mb-2 tw--mt-2">
  <h3 class="tw-mb-1 tw-mr-4">
    {{ trans("$tpl.index") }}
    <span class="tw-text-base text-muted tw-whitespace-no-wrap">
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
  @if (!empty($search_form))
    <form class="tw-my-1 tw-mr-2">
      <input name="q" class="form-control" placeholder="{{ ViewHelper::modelFieldTrans($model_tpl, 'q_placeholder') }}" value="{{ $q ?? '' }}" autocapitalize="none">
    </form>
  @endif
  @yield('heading-after-search')
</div>

@yield('toolbar')

@if (!empty($filters = Request::except(['filter', 'page', 'sd', 'sk', '_pjax'])))
  <div class="tw-my-2">
    <a class="btn btn-default tw-my-1" href="{{ path("$self@index") }}">
      {{ trans('acp.reset_filters') }}
    </a>
    @foreach ($filters as $key => $value)
      <a class="btn btn-default tw-my-1" href="{{ fullUrl(array_merge($filters, ['page' => null, $key => null, '_pjax' => null])) }}">
        {{ $key }}: {{ $value }}
        <span class="tw-text-red-600">
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
