@extends('acp.base')

<?php Form::model($model); ?>

@section('content')
<h3>
  @include('acp.tpl.back')
  {{ trans("$tpl.create") }}
</h3>
<form action="{{ path("$self@store") }}" class="mt-3 {{ sizeof($errors) ? 'was-validated' : '' }}" method="post" enctype="multipart/form-data">
  {{ ViewHelper::inputHiddenMail() }}

  @include("$tpl.form")
  @include('acp.tpl.hidden_fields')

  <div class="form-group form-row acp-sticky-buttons mb-0">
    <div class="col-lg-9 offset-lg-3">
      <button class="btn btn-primary">
        {{ trans("$tpl.add") }}
      </button>
    </div>
  </div>
</form>
@endsection
