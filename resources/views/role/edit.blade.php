@extends('layouts.backend')

@section('content')
<form id="role-edit" action="/role-update/{{ $role->id }}" method="POST">
    @csrf
    <x-laraknife.edit-panel title="{{ __('Change of a Role') }}">
        <x-laraknife.text position="first" name="name" label="Name" value="{{ $role->name }}" width2="4"/>
        <x-laraknife.text position="last" name="priority" label="Priority" value="{{ $role->priority }}" width2="4"/>
    </x-laraknife.edit-panel>
</form>
@endsection
