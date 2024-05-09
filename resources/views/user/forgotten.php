@extends('layouts.backend')

@section('content')
    <form id="user-edit" action="/user-forgotten" method="POST">
        @csrf
        <x-laraknife.panels.edit title="{{ __('Password Forgotten') }}">
            <x-laraknife.forms.string position="alone" name="email" label="Email" value="{{ $context->valueOf('email') }}" width1="2" width2="4" />
            <x-laraknife.buttons.button-position position="alone" name="btnSend" label="Send" />
        </x-laraknife.panels.edit>
    </form>
@endsection
