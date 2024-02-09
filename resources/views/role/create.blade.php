@extends('layouts.backend')

@section('content')
<form id="role-create" action="/role-store" method="POST">
    @csrf
    @method('put')
    <x-laraknife.panels.create title="{{ __('Creation of a Role') }}">
        <x-laraknife.forms.text position="first" name="name" label="Name" width2="4" />
        <x-laraknife.forms.text position="last" name="priority" label="Priority" width2="4" />
    </x-laraknife.panels.create>
</form>
@endsection
