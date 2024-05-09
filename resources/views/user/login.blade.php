@extends('layouts.backend')

@section('content')
    <form id="user-edit" action="/user-login" method="POST">
        @csrf
        <x-laraknife.panels.standard title="{{ __('Login') }}">
            <x-laraknife.forms.set-position position="first" />
            <x-laraknife.layout.col-empty width="3" />
            <x-laraknife.forms.string position="last" name="email" label="Email" value="{{ $context->valueOf('email') }}"
                width2="4" />
            <x-laraknife.forms.set-position position="first" />
            <x-laraknife.layout.col-empty width="3" />
            <x-laraknife.forms.password position="last" name="password" label="Password" value="" width2="4" />
            <x-laraknife.forms.set-position position="first" />
            <x-laraknife.layout.col-empty width="5" />
            <x-laraknife.forms.checkbox position="last" name="autologin" label="Remain signed in" labelBelow="true" width2="4" />
            <x-laraknife.forms.set-position position="first" />
            <x-laraknife.layout.col-empty width="3" />
            <x-laraknife.buttons.button-position position="last" name="btnLogin" label="Login" />
            <x-laraknife.layout.row-empty />
            <x-laraknife.forms.link position="alone" reference="/user-forgotten" text="{{__('Password forgotten?')}}" width1="5" width2="4" />
        </x-laraknife.panels.standard>
    </form>
@endsection
