@extends('layouts.backend')

@section('content')
    <form id="hour-create" action="/hour-store" method="POST">
        @csrf
        @method('PUT')
        <x-laraknife.panels.create title="{{ __('Creation of Hours') }}">
            <x-laraknife.forms.combobox position="first" name="hourtype_scope" label="Hourtype" :options="$optionsHourtype"
                width2="4" />
            <x-laraknife.forms.combobox position="last" name="hourstate_scope" label="Hourstate" :options="$optionsHourstate"
                width2="4" />
            <x-laraknife.forms.string type="date" position="first" name="time" label="Date"
                value="{{ $context->valueOf('time') }}" width2="4" />
            <x-laraknife.forms.string type="number" position="last" name="duration" label="Duration (minutes)"
                value="{{ $context->valueOf('duration') }}" width2="4" />
            <x-laraknife.forms.string type="time" position="first" name="start" label="Start"
                value="{{ $context->valueOf('start') }}" width2="4" />
            <x-laraknife.forms.string type="time" position="last" name="end" label="End"
                value="{{ $context->valueOf('end') }}" width2="4" />
            <x-laraknife.forms.string type="number" position="first" name="factor" label="Factor"
                value="{{ $context->valueOf('factor') }}" width2="4" />
            <x-laraknife.forms.combobox position="last" name="owner_id" label="Owner" :options="$optionsOwner" width2="4" />
            <x-laraknife.forms.text position="alone" name="description" label="Description"
                value="{{ $context->valueOf('description') }}" width2="10" rows="2" />
        </x-laraknife.panels.create>
    </form>
@endsection
