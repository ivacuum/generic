<?php
/** @var $model */
?>

@extends('acp.base')

@section('content_header')
<div class="lg:flex lg:-mx-4">
  <div class="lg:w-1/4 lg:px-4">
    <div class="flex flex-col w-full">
      @can('show', $model)
        <a
          class="border-l-2 border-transparent px-3 py-2 {{ $view === "$tpl.show" ? 'border-orange-600 text-black hover:text-black' : '' }}"
          href="{{ path([$controller, 'show'], $model) }}"
        >
          {{ trans("$tpl.show") }}
        </a>
      @endcan
      @can('edit', $model)
        <a
          class="border-l-2 border-transparent px-3 py-2 {{ $view === "$tpl.edit" ? 'border-orange-600 text-black hover:text-black' : '' }}"
          href="{{ UrlHelper::edit($controller, $model) }}"
        >
          {{ trans("$tpl.edit") }}
        </a>
      @endcan
      @yield('model_menu')
      @if (isset($modelRelations) && sizeof($modelRelations))
        <?php /** @var array $relation */ ?>
        @foreach ($modelRelations as $relation)
          <a class="border-l-2 border-transparent px-3 py-2" href="{{ $relation['path'] }}">
            {{ trans("acp.{$relation['i18n_index']}.index") }}
            <span class="text-muted text-xs whitespace-no-wrap">{{ ViewHelper::number($relation['count']) }}</span>
          </a>
        @endforeach
      @endif
      @if (method_exists($model, 'www'))
        <a class="border-l-2 border-transparent px-3 py-2" href="{{ $model->www() }}">
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
