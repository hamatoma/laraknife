@extends('layouts.backend')

@section('content')
    <form id="person-edit" action="/person-update/{{ $context->model->id }}" method="POST">
        @csrf
        <x-laraknife.panels.standard title="{{ __('Change of a Person') }}" fieldset="false">
            <x-laraknife.layout.nav-tabs :info="$navTabsInfo" fieldset="true" >
                <x-laraknife.forms.combobox position="first" name="gender_scope" label="Gender" :options="$optionsGender"
                    width2="4" />
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
                <x-laraknife.forms.text position="alone" name="info" label="Info"
                    value="{{ $context->valueOf('info') }}" width2="10" rows="2" />
            </x-laraknife.layout.nav-tabs>
        </x-laraknife.panels.standard>
    </form>
@endsection
