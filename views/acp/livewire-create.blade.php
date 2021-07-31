@extends('acp.base')
@include('livewire')

@section('content')
<h3 class="text-2xl">
  @include('acp.tpl.back')
  @lang("$tpl.create")
</h3>

@include("$tpl.form")
@endsection
