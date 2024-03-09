@extends('layouts.backend')

@section('content')
    <form id="term-create" action="/term-store" method="POST">
        @csrf
        @method('PUT')
        <x-laraknife.panels.create title="{{ __('Creation of a Term') }}">
            <x-laraknife.forms.combobox position="first" name="visibility_scope" label="Visibility" :options="$optionsVisibility"
                width2="4" />
            <x-laraknife.forms.combobox position="last" name="owner_id" label="Owner" :options="$optionsOwner" width2="4" />
            <x-laraknife.forms.string type="datetime-local" position="first" name="term" label="Term"
                value="{{ $context->valueOf('term') }}" width2="4" />
            <x-laraknife.forms.string position="last" name="duration" label="Duration" value="{{ $context->valueOf('duration') }}"
                width2="4" />
            <x-laraknife.forms.string position="alone" name="title" label="Title" value="{{ $context->valueOf('title') }}"
                width2="10" />
            <x-laraknife.forms.text position="alone" name="description" label="Description"
                value="{{ $context->valueOf('description') }}" width2="10" rows="5" />
        </x-laraknife.panels.create>
    </form>
@endsection
