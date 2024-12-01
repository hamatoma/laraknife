@extends('layouts.backend')

@section('content')
    <form id="address-create" action="/address-store" method="POST">
        @csrf
        @method('PUT')
        <x-laraknife.panels.create title="{{ __('Creation of an Address') }}">
            <x-laraknife.forms.combobox position="first" name="addresstype_scope" label="Addresstype" :options="$optionsAddresstype"
                width2="4" />
            <x-laraknife.forms.combobox position="last" name="owner_id" label="Owner" :options="$optionsOwner" width2="4" />
            <x-laraknife.forms.string position="first" name="name" label="Email/Phone"
                value="{{ $context->valueOf('name') }}" width2="4" />
            <x-laraknife.forms.string position="last" name="priority" label="Priority"
                value="{{ $context->valueOf('priority') }}" width2="4" />
            <x-laraknife.forms.text position="alone" name="info" label="Info" value="{{ $context->valueOf('info') }}"
                width2="10" rows="2" />
        </x-laraknife.panels.create>
    </form>
@endsection
