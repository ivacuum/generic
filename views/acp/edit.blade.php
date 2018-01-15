@extends("$tpl.base")

<?php Form::model($model); ?>

@section('content')
<form action="{{ path("$self@update", $model) }}" class="mt-3" method="post" enctype="multipart/form-data">
  {{ ViewHelper::inputHiddenMail() }}

  @include("$tpl.form")

  <div class="form-row sticky-bottom-buttons">
    <div class="col-md-8 offset-md-4">
      <button class="btn btn-primary">
        {{ trans('acp.save') }}
      </button>
      <button name="_save" class="btn btn-default">
        {{ trans('acp.apply') }}
      </button>
    </div>
  </div>

  @include('acp.tpl.hidden_fields', ['method' => 'put'])
</form>
@endsection
