@extends('layouts.backend')

@section('content')
    <form id="location-edit" action="/location-update/{{ $context->model->id }}" method="POST">
        @csrf
        <x-laraknife.panels.edit title="{{ __('Change of a Location') }}">
            <x-laraknife.forms.combobox position="first" name="person_id" label="Person" :options="$optionsPerson" width2="4"
                attribute="readonly" />
            <x-laraknife.forms.string position="last" name="country" label="Country"
                value="{{ $context->valueOf('country') }}" width2="4" />
            <x-laraknife.forms.string position="first" name="zip" label="Zip" value="{{ $context->valueOf('zip') }}"
                width2="4" />
            <x-laraknife.forms.string position="last" name="city" label="City" value="{{ $context->valueOf('city') }}"
                width2="4" />
            <x-laraknife.forms.string position="first" name="street" label="Street"
                value="{{ $context->valueOf('street') }}" width2="4" />
            <x-laraknife.forms.string position="last" name="additional" label="Additional"
                value="{{ $context->valueOf('additional') }}" width2="4" />
            <x-laraknife.forms.text position="first" name="info" label="Info" value="{{ $context->valueOf('info') }}"
                width2="4" rows="2" />
            <x-laraknife.forms.string position="last" name="priority" label="Priority"
                value="{{ $context->valueOf('priority') }}" width2="4" />
        </x-laraknife.panels.edit>
    </form>
@endsection
