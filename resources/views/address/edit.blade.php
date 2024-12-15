@extends('layouts.backend')

@section('content')
    <form id="address-edit" action="/address-update/{{ $context->model->id }}" method="POST">
        @csrf
        <x-laraknife.panels.edit title="{{ __('Change of an Address') }}">
            <x-laraknife.forms.combobox position="first" name="addresstype_scope" label="Addresstype" :options="$optionsAddresstype"
                width2="4" attribute="readonly" />
                <x-laraknife.forms.combobox position="last" name="person_id" label="Person" :options="$optionsPerson" width2="4"
                    attribute="readonly" />
            <x-laraknife.forms.string position="alone" name="name" label="Name" value="{{ $context->valueOf('name') }}"
                width2="4" />
            <x-laraknife.forms.text position="alone" name="info" label="Info" value="{{ $context->valueOf('info') }}"
                width2="10" rows="2" />
        </x-laraknife.panels.edit>
    </form>
@endsection
