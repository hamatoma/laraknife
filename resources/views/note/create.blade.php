@extends('layouts.backend')

@section('content')
    <form id="note-create" action="/note-store" method="POST">
        @csrf
        @method('PUT')
        <x-laraknife.panels.create title="{{ __('Creation of a Note') }}">
            <x-laraknife.forms.combobox position="first" name="category_scope" label="Category" :options="$optionsCategory"
                width2="4" />
            <x-laraknife.forms.combobox position="last" name="notestatus_scope" label="Status" :options="$optionsNotestatus"
                width2="4" />
            <x-laraknife.forms.combobox position="first" name="visibility_scope" label="Visibility" :options="$optionsVisibility" width2="4" />
            <x-laraknife.forms.combobox position="last" name="user_id" label="User" :options="$optionsUser" width2="4" />
            <x-laraknife.forms.string position="alone" name="title" label="Title"
                value="{{ $context->valueOf('title') }}" width2="10" />
            <x-laraknife.forms.text position="alone" name="body" label="Body" value="{{ $context->valueOf('body') }}"
                width2="10" rows="10" />
        </x-laraknife.panels.create>
    </form>
@endsection
