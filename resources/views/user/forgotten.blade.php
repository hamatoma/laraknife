@extends('layouts.backend')

@section('content')
    <form id="user-edit" action="/user-forgotten" method="POST">
        @csrf
        <x-laraknife.panels.standard title="{{ __('Password forgotten?') }}" button1Name="" button2Name="">
            <x-laraknife.forms.set-position position="first" />
            <x-laraknife.layout.col-empty width="3" />
            <x-laraknife.forms.string position="last" name="email" label="Email" value="{{ $context->valueOf('email') }}" width1="2" width2="4" />
            <x-laraknife.buttons.button-position position="alone" name="btnSend" label="Send" width1="5" width2="4"/>
            <x-laraknife.forms.set-position position="first" />
            <x-laraknife.layout.col-empty width="3" />
            <x-laraknife.forms.link position="last" reference="/user-login" text="{{ __('Login') }}" label="{{$context->getSnippet('msg')}}" width1="2" width2="3"/>
        </x-laraknife.panels.standard>
    </form>
@endsection
