@extends('layouts.backend')

@section('content')
    <form id="hour-edit" action="/hour-update/{{ $context->model->id }}" method="POST">
        @csrf
        <x-laraknife.panels.edit title="{{ __('Change of Hours') }}">
            <x-laraknife.forms.combobox position="first" name="hourtype_scope" label="Hourtype" :options="$optionsHourtype"
                width2="4" />
            <x-laraknife.forms.combobox position="last" name="hourstate_scope" label="Hourstate" :options="$optionsHourstate"
                width2="4" />
            <x-laraknife.forms.string type="datetime-local" position="first" name="time" label="Time"
                value="{{ $context->valueOf('time') }}" width2="4" />
            <x-laraknife.forms.string type="number" position="last" name="duration" label="Duration (minutes)"
                value="{{ $context->valueOf('duration') }}" width2="4" />
            <x-laraknife.forms.string type="number" position="first" name="factor" label="Factor"
                value="{{ $context->valueOf('factor') }}" width2="4" />
            <x-laraknife.forms.combobox position="last" name="owner_id" label="Owner" :options="$optionsOwner" width2="4" />
            <x-laraknife.forms.string type="number" position="alone" name="interested" label="Interested"
                value="{{ $context->valueOf('interested') }}" width2="4" />
            <x-laraknife.forms.text position="alone" name="description" label="Description"
                value="{{ $context->valueOf('description') }}" width2="10" rows="2" />
        </x-laraknife.panels.edit>
    </form>
@endsection
