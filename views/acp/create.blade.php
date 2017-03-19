@extends('acp.base')

<?php Form::model($model); ?>

@section('content')
<h3 class="mt-0">
  @include('acp.tpl.back')
  {{ trans("$tpl.create") }}
</h3>
<form action="{{ action("$self@store") }}" class="form-horizontal" method="post">

  @include("$tpl.form")
  @include('acp.tpl.hidden_fields')

  <div class="form-group">
    <div class="col-md-9 col-md-offset-3">
      <button type="submit" class="btn btn-primary">
        {{ trans("$tpl.add") }}
      </button>
    </div>
  </div>
</form>
@endsection
