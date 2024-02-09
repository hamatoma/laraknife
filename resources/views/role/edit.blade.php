@extends('layouts.backend')

@section('content')
<form id="role-edit" action="/role-update/{{ $context->model->id }}" method="POST">
    @csrf
    <x-laraknife.panels.edit title="{{ __('Change of a Role') }}">
        <x-laraknife.forms.text position="first" name="name" label="Name" value="{{ $context->valueOf('name') }}" width2="4"/>
        <x-laraknife.forms.text position="last" name="priority" label="Priority" value="{{ $context->valueOf('priority') }}" width2="4"/>
    </x-laraknife.panels.edit>
</form>
@endsection
