@extends('layouts.backend')

@section('content')
    <form id="user-edit" action="/user-update/{{ $context->model->id }}" method="POST">
        @csrf
        <x-laraknife.panels.edit title="{{ __('Change of an User') }}">
            <x-laraknife.forms.string position="first" name="name" label="Name" value="{{ $context->valueOf('name') }}"
                width2="4" />
            <x-laraknife.forms.string position="last" name="email" label="Email" value="{{ $context->valueOf('email') }}"
                width2="4" />
            <x-laraknife.forms.combobox position="first" name="role_id" label="Role" :options="$roleOptions" width2="4" />
            <x-laraknife.buttons.button name="btnSetPassword" label="{{ __('Set Password') }}" />
            <x-laraknife.forms.set-position position="last" />
            <x-laraknife.forms.combobox position="alone" name="localization" label="Localization" :options="$localizationOptions"
                width2="4" />
        </x-laraknife.panels.edit>
    </form>
@endsection
