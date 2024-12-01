@extends('layouts.backend')

@section('content')
    <form id="address-edit" action="/address-update/{{ $context->model->id }}" method="POST">
        @csrf
        <x-laraknife.panels.edit title="{{ __('Change of an Address') }}">
            <x-laraknife.forms.combobox position="first" name="addresstype_scope" label="Addresstype" :options="$optionsAddresstype"
                width2="4" attribute="readonly" />
                <x-laraknife.forms.combobox position="last" name="owner_id" label="Owner" :options="$optionsOwner" width2="4"
                    attribute="readonly" />
            <x-laraknife.forms.string position="first" name="name" label="Name" value="{{ $context->valueOf('name') }}"
                width2="4" />
            <x-laraknife.forms.string position="last" name="name" label="Name" value="{{ $context->valueOf('name') }}"
                width2="4" />
            <x-laraknife.forms.text position="alone" name="info" label="Info" value="{{ $context->valueOf('info') }}"
                width2="10" rows="2" />
        </x-laraknife.panels.edit>
    </form>
@endsection
