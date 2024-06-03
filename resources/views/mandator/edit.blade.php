@extends('layouts.backend')

@section('content')
    <form id="mandator-edit" action="/mandator-update/{{ $context->model->id }}" method="POST">
        @csrf
        <x-laraknife.panels.standard title="{{ __('Change of a Mandator') }}" fieldset="false">
            <x-laraknife.layout.nav-tabs :info="$navTabsInfo" fieldset="true">
                <x-laraknife.forms.string position="first" name="name" label="Name" value="{{ $context->valueOf('name') }}"
                    width2="4" />
                <x-laraknife.forms.combobox position="last" name="group_id" label="Group" :options="$optionsGroup"
                    width2="4" />
                <x-laraknife.forms.text position="alone" name="info" label="Info"
                    value="{{ $context->valueOf('info') }}" width2="10" rows="3" />
            </x-laraknife.layout.nav-tabs>
        </x-laraknife.panels.standard>
    </form>
@endsection
