@extends('layouts.backend')

@section('content')
    <form enctype="multipart/form-data" id="note-create_document" action="/note-store_document/{{ $note->id }}"
        method="POST">
        @csrf
        @method('PUT')
        <x-laraknife.panels.create title="{{ __('Creation of a Document') }}">
            <x-laraknife.forms.combobox position="first" name="filegroup_scope" label="Filegroup" :options="$optionsFilegroup"
                width2="4" />
            <x-laraknife.forms.combobox position="last" name="visibility_scope" label="Visibility" :options="$optionsVisibility"
                width2="4" />
            <x-laraknife.forms.file position="first" name="file" label="File" width2="4" />
            <x-laraknife.forms.string position="last" name="filename" label="Filename"
                value="{{ $context->valueOf('filename') }}" width2="4"
                placeholder="{{ __('Overwrites the upload name (if not empty)') }}" />
            <x-laraknife.forms.string position="alone" name="title" label="Title"
                value="{{ $context->valueOf('title') }}" width2="10" />
            <x-laraknife.forms.text position="alone" name="description" label="Description"
                value="{{ $context->valueOf('description') }}" width2="10" rows="4" />
        </x-laraknife.panels.create>
    </form>
@endsection
