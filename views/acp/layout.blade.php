@extends('acp.base')

@section('content_header')
<div class="row">
  <div class="col-sm-3">
    <div class="list-group text-center">
      @can('show', $model)
        <a class="list-group-item {{ $view === "$tpl.show" ? 'active' : '' }}" href="{{ path("$self@show", $model) }}">
          {{ trans("$tpl.show") }}
        </a>
      @endcan
      @can('edit', $model)
        <a class="list-group-item {{ $view === "$tpl.edit" ? 'active' : '' }}" href="{{ UrlHelper::edit($self, $model) }}">
          {{ trans("$tpl.edit") }}
        </a>
      @endcan
      @yield('model_menu')
      @if (is_array($show_with_count))
        @foreach ($show_with_count as $field)
          @php ($related = $model->{$field}()->getRelated())
          @can('list', $related)
            @php ($controller = \Ivacuum\Generic\Utilities\NamingHelper::controllerName($related))
            @php ($trans_field = \Ivacuum\Generic\Utilities\NamingHelper::transField($related))
            @php ($count_field = "{$field}_count")
            @if ($model->{$count_field})
              <a class="list-group-item" href="{{ path("Acp\\{$controller}@index", [$model->getForeignKey() => $model->getKey()]) }}">
                {{ trans("acp.{$trans_field}.index") }}
                <span class="text-muted small">{{ ViewHelper::number($model->{$count_field}) }}</span>
              </a>
            @endif
          @endcan
        @endforeach
      @endif
      @if (method_exists($model, 'www'))
        <a class="list-group-item" href="{{ $model->www() }}">
          {{ trans('acp.www') }}
          @svg (external-link)
        </a>
      @endif
      @include('acp.tpl.delete')
    </div>
  </div>
  <div class="col-sm-9">
    <h2 class="mt-0 text-break-word">
      @include('acp.tpl.back')
      @section('model_title')
        {{ $model->breadcrumb() }}
      @show
    </h2>
@endsection

@section('content_footer')
  </div>
</div>
@endsection
