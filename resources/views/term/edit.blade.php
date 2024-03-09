@extends('layouts.backend')

@section('content')
    <form id="term-edit" action="/term-update/{{ $context->model->id }}" method="POST">
        @csrf
        <x-laraknife.panels.edit title="{{ __('Change of a Term') }}">
            <x-laraknife.forms.combobox position="first" name="visibility_scope" label="Visibility" :options="$optionsVisibility"
                width2="4" />
            <x-laraknife.forms.combobox position="last" name="owner_id" label="Owner" :options="$optionsOwner" width2="4" />
            <x-laraknife.forms.string type="datetime-local" position="first" name="term" label="Term"
                value="{{ $context->valueOf('term') }}" width2="4" />
            <x-laraknife.forms.string position="last" name="duration" label="Duration"
                value="{{ $context->valueOf('duration') }}" valueTime="{{ $context->valueOf('duration') }}"
                width2="4" />
            <x-laraknife.forms.string position="alone" name="title" label="Title" value="{{ $context->valueOf('title') }}"
                width2="10" />
            <x-laraknife.forms.text position="alone" name="description" label="Description"
                value="{{ $context->valueOf('description') }}" width2="10" rows="10" />
        </x-laraknife.panels.edit>
    </form>
@endsection
