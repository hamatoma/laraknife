@extends('layouts.backend')

@section('content')
<form id="role-create" action="/role-create" method="POST">
    @csrf
    <x-laraknife.create-panel title="{{ __('Creation of a Role') }}">
        <x-laraknife.text position="first" name="name" label="Name" width2="4" />
        <x-laraknife.text position="last" name="priority" label="Priority" width2="4" />
    </x-laraknife.create-panel>
</form>
@endsection
