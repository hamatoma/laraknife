@extends('layouts.backend')

@section('content')
<form id="sproperty-edit" action="/sproperty-update/{{ $context->valueOf('id') }}" method="POST">
    @csrf
    <x-laraknife.panels.edit title="{{ __('Change of a Scoped Property') }}">

        <x-laraknife.forms.string position="first" name="id" label="Id" value="{{ $context->valueOf('id') }}" width2="4"
            attribute="readonly" />
        <x-laraknife.forms.string position="last" name="scope" label="Scope" value="{{ $context->valueOf('scope') }}" width2="4"
            attribute="readonly" />

        <x-laraknife.forms.string position="first" name="name" label="Name" value="{{ $context->valueOf('name') }}"
            width2="4" />
        <x-laraknife.forms.string position="last" name="shortname" label="Shortname" value="{{ $context->valueOf('shortname') }}"
            width2="4" />

        <x-laraknife.forms.string position="first" name="order" label="Order" width2="4"
            value="{{ $context->valueOf('order') }}" />
        <x-laraknife.forms.string position="last" name="value" label="Value" width2="4"
            value="{{ $context->valueOf('value') }}" />

        <x-laraknife.forms.text position="alone" name="info" label="Info" value="{{ $context->valueOf('info') }}"
            width2="10" rows="4" />
    </x-laraknife.panels.edit>
</form>
@endsection
