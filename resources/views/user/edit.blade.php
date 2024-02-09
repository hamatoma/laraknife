@extends('layouts.backend')

@section('content')
    <form id="user-edit" action="/user-update/{{ $context->model->id }}" method="POST">
        @csrf
        <x-laraknife.panels.edit title="{{ __('Change of an User') }}">
            <x-laraknife.forms.text position="first" name="name" label="Name" value="{{ $context->valueOf('name') }}" width2="4" />
            <x-laraknife.forms.text position="last" name="email" label="Email" value="{{ $context->valueOf('email') }}" width2="4" />
            <x-laraknife.forms.combobox position="first" name="role_id" label="Role" :options="$roleOptions" width2="4" />
            <x-laraknife.buttons.button name="btnSetPassword" label="{{ __('Set Password') }}" />
            <x-laraknife.forms.set-position position="last" />
        </x-laraknife.panels.edit>
    </form>
@endsection
