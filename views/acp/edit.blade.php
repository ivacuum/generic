@extends("$tpl.base")

<?php Form::model($model); ?>

@section('content')
<form action="{{ path("$self@update", $model) }}" class="tw-mt-4" method="post" enctype="multipart/form-data">
  {{ ViewHelper::inputHiddenMail() }}

  @include("$tpl.form")

  <div class="sticky-bottom-buttons">
    <button class="btn btn-primary">
      {{ trans('acp.save') }}
    </button>
    <button name="_save" class="btn btn-default">
      {{ trans('acp.apply') }}
    </button>
  </div>

  @include('acp.tpl.hidden_fields', ['method' => 'put'])
</form>
@endsection
