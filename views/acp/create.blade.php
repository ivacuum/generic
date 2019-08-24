@extends('acp.base')

<?php Form::model($model); ?>

@section('content')
<h3 class="tw-text-2xl">
  @include('acp.tpl.back')
  {{ trans("$tpl.create") }}
</h3>
<form action="{{ path("$self@store") }}" class="tw-mt-4" method="post" enctype="multipart/form-data">
  {{ ViewHelper::inputHiddenMail() }}

  @include("$tpl.form")
  @include('acp.tpl.hidden_fields')

  <div class="sticky-bottom-buttons">
    <button class="btn btn-primary">
      {{ trans("$tpl.add") }}
    </button>
  </div>
</form>
@endsection
