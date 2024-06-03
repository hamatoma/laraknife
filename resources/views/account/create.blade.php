@extends('layouts.backend')

@section('content')
    <form id="account-create" action="/account-store/{{ $context->valueOf('mandator_id') }}" method="POST">
        @csrf
        @method('PUT')
        <x-laraknife.panels.create title="{{ __('Creation of an Account') }}">
            <x-laraknife.forms.string position="alone" name="mandator_id" label="Mandator"
                value="{{ $context->valueOf('mandator') }}" width2="4" attribute="readonly" />
            <x-laraknife.forms.string position="first" name="name" label="Name" value="{{ $context->valueOf('name') }}"
                width2="4" />
            <x-laraknife.forms.string position="last" name="amount" label="Amount"
                value="{{ $context->valueOf('amount') }}" width2="4" />
            <x-laraknife.forms.text position="alone" name="info" label="Info" value="{{ $context->valueOf('info') }}"
                width2="10" rows="3" />
        </x-laraknife.panels.create>
    </form>
@endsection
