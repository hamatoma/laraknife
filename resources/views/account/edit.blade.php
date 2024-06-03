@extends('layouts.backend')

@section('content')
    <form id="account-edit" action="/account-edit/{{ $context->model->id }}" method="POST">
        @csrf
        <x-laraknife.panels.standard title="{{ __('Change of an Account') }}" fieldset="false">
            <x-laraknife.layout.nav-tabs :info="$navTabsInfo" fieldset="true">
                <x-laraknife.forms.string position="alone" name="mandator" label="Mandator" value="{{ $context->valueOf('mandator') }}"
                    width2="4" attribute="readonly"/>
                <x-laraknife.forms.string position="first" name="name" label="Name" value="{{ $context->valueOf('name') }}"
                    width2="4" />
                <x-laraknife.forms.string position="last" name="amount" label="Amount"
                    value="{{ $context->valueOf('amount') }}" width2="4" attribute="readonly" />
                <x-laraknife.forms.text position="alone" name="info" label="Info"
                    value="{{ $context->valueOf('info') }}" width2="10" rows="3" />
            </x-laraknife.layout.nav-tabs>
        </x-laraknife.panels.standard>
    </form>
@endsection
