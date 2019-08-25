@extends('acp.base')

@section('content_header')
<div class="lg:flex lg:-mx-4">
  <div class="lg:w-1/4 lg:px-4">
    <div class="list-group text-center">
      @can('show', $model)
        <a class="list-group-item list-group-item-action {{ $view === "$tpl.show" ? 'active' : '' }}" href="{{ path("$self@show", $model) }}">
          {{ trans("$tpl.show") }}
        </a>
      @endcan
      @can('edit', $model)
        <a class="list-group-item list-group-item-action {{ $view === "$tpl.edit" ? 'active' : '' }}" href="{{ UrlHelper::edit($self, $model) }}">
          {{ trans("$tpl.edit") }}
        </a>
      @endcan
      @yield('model_menu')
      @if (is_array($show_with_count))
        @foreach ($show_with_count as $field)
          @php ($related = $model->{$field}()->getRelated())
          @can('list', $related)
            @php ($controller = Ivacuum\Generic\Utilities\NamingHelper::controllerName($related))
            @php ($trans_field = Ivacuum\Generic\Utilities\NamingHelper::transField($related))
            @php ($count_field = snake_case($field).'_count')
            @if ($model->{$count_field})
              <a class="list-group-item list-group-item-action" href="{{ path("Acp\\{$controller}@index", [$model->getForeignKey() => $model->getKey()]) }}">
                {{ trans("acp.{$trans_field}.index") }}
                <span class="text-muted text-xs whitespace-no-wrap">{{ ViewHelper::number($model->{$count_field}) }}</span>
              </a>
            @endif
          @endcan
        @endforeach
      @endif
      @if (method_exists($model, 'www'))
        <a class="list-group-item list-group-item-action" href="{{ $model->www() }}">
          {{ trans('acp.www') }}
          @svg (external-link)
        </a>
      @endif
      @include('acp.tpl.delete')
    </div>
    @yield('model_menu_after')
  </div>
  <div class="lg:w-3/4 lg:px-4">
    <h2 class="mt-4 lg:mt-0 break-words">
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
