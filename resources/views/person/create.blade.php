@extends('layouts.backend')

@section('content')
    <form id="person-create" action="/person-store" method="POST">
        @csrf
        @method('PUT')
        <x-laraknife.panels.create title="{{ __('Creation of a Person') }}">
            <x-laraknife.forms.combobox position="first" name="gender_scope" label="Gender" :options="$optionsGender" width2="4" />
            <x-laraknife.forms.combobox position="last" name="persongroup_scope" label="Persongroup" :options="$optionsPersongroup"
                width2="4" />
            <x-laraknife.forms.string position="first" name="lastname" label="Lastname"
                value="{{ $context->valueOf('lastname') }}" width2="4" />
            <x-laraknife.forms.string position="last" name="firstname" label="Firstname"
                value="{{ $context->valueOf('firstname') }}" width2="4" />
            <x-laraknife.forms.string position="first" name="nickname" label="Nickname"
                value="{{ $context->valueOf('nickname') }}" width2="4" />
            <x-laraknife.forms.string position="last" name="middlename" label="Middlename"
                value="{{ $context->valueOf('middlename') }}" width2="4" />
            <x-laraknife.forms.string position="alone" name="titles" label="Titles"
                value="{{ $context->valueOf('titles') }}" width2="4" />
            <x-laraknife.forms.text position="alone" name="info" label="Info" value="{{ $context->valueOf('info') }}"
                width2="10" rows="2" />

        </x-laraknife.panels.create>
    </form>
@endsection
