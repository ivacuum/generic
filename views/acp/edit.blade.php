@extends("$tpl.base")

<?php Form::model($model); ?>

@section('content')
<form action="{{ path("$self@update", $model) }}" class="mt-3 {{ sizeof($errors) ? 'was-validated' : '' }}" method="post" enctype="multipart/form-data">
  {{ ViewHelper::inputHiddenMail() }}

  @include("$tpl.form")

  <div class="form-group form-row acp-sticky-buttons mb-0">
    <div class="col-lg-9 offset-lg-3">
      <button class="btn btn-primary">
        {{ trans('acp.save') }}
      </button>
      <button name="_save" class="btn btn-light border">
        {{ trans('acp.apply') }}
      </button>
    </div>
  </div>

  @include('acp.tpl.hidden_fields', ['method' => 'put'])
</form>
@endsection
