@extends('acp.base')

<?php Form::model($model); ?>

@section('content')
<h3>
  @include('acp.tpl.back')
  {{ trans("$tpl.create") }}
</h3>
<form action="{{ path("$self@store") }}" class="mt-3" method="post" enctype="multipart/form-data">
  {{ ViewHelper::inputHiddenMail() }}

  @include("$tpl.form")
  @include('acp.tpl.hidden_fields')

  <div class="form-row sticky-bottom-buttons">
    <div class="col-md-8 offset-md-4">
      <button class="btn btn-primary">
        {{ trans("$tpl.add") }}
      </button>
    </div>
  </div>
</form>
@endsection
