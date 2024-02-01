@extends('layouts.backend')

@section('content')
<form id="role-edit" action="/role-update/{{ $context->model->id }}" method="POST">
    @csrf
    <x-laraknife.edit-panel title="{{ __('Change of a Role') }}">
        <x-laraknife.text position="first" name="name" label="Name" value="{{ $context->valueOf('name') }}" width2="4"/>
        <x-laraknife.text position="last" name="priority" label="Priority" value="{{ $context->valueOf('priority') }}" width2="4"/>
    </x-laraknife.edit-panel>
</form>
@endsection
