@extends("$tpl.base")

<?php Form::model($model); ?>

@section('content')
<form action="{{ path("$self@update", $model) }}" class="form-horizontal" method="post" enctype="multipart/form-data">
  {{ ViewHelper::inputHiddenMail() }}

  @include("$tpl.form")

  <div class="form-group">
    <div class="col-md-9 col-md-offset-3">
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
