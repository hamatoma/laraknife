@extends('layouts.backend')

@section('content')
    <form id="mandator-create" action="/mandator-store" method="POST">
        @csrf
        @method('PUT')
        <x-laraknife.panels.create title="{{ __('Creation of a Mandator') }}">
            <x-laraknife.forms.string position="first" name="name" label="Name" value="{{ $context->valueOf('name') }}"
                width2="4" />
            <x-laraknife.forms.combobox position="last" name="group_id" label="Group" :options="$optionsGroup" width2="4" />
            <x-laraknife.forms.text position="alone" name="info" label="Info" value="{{ $context->valueOf('info') }}"
                width2="10" rows="3" />
        </x-laraknife.panels.create>
    </form>
@endsection
