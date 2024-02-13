@extends('layouts.backend')

@section('content')
    <form id="user-edit" action="/user-login" method="POST">
        @csrf
        <x-laraknife.panels.standard title="{{ __('Login') }}">
            <x-laraknife.forms.set-position position="first" />
            <x-laraknife.layout.col-empty width="3" />
            <x-laraknife.forms.text position="last" name="email" label="Email" value="{{ $context->valueOf('email') }}"
                width2="4" />
            <x-laraknife.forms.set-position position="first" />
            <x-laraknife.layout.col-empty width="3" />
            <x-laraknife.forms.password position="last" name="password" label="Password" value="" width2="4" />
            <x-laraknife.forms.set-position position="first" />
            <x-laraknife.layout.col-empty width="3" />
            <x-laraknife.buttons.button-position position="last" name="btnLogin" label="{{ __('Login') }}" />
        </x-laraknife.panels.standard>
    </form>
@endsection
