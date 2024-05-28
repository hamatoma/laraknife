@extends('layouts.backend')

@section('content')
    <form id="note-update_document" action="/note-update_document/{{ $context->model->id }}" method="POST">
        @csrf
        <x-laraknife.panels.edit title="{{ __('Change of a File') }}" button1Name="" button2Name="">
            <x-laraknife.forms.string position="alone" name="title" label="Title" value="{{ $context->valueOf('title') }}"
                width2="10" />
            <x-laraknife.forms.text position="alone" name="description" label="Description"
                value="{{ $context->valueOf('description') }}" width2="10" rows="4" />
            <x-laraknife.forms.combobox position="first" name="visibility_scope" label="Visibility" :options="$optionsVisibility"
                width2="4" />
            <x-laraknife.forms.combobox position="last" name="filegroup_scope" label="Filegroup" :options="$optionsFilegroup"
                width2="4" />
            <x-laraknife.forms.string position="alone" name="filename" label="Filename"
                value="{{ $context->valueOf('filename') }}" width2="10" attribute="readonly" />
            <x-laraknife.forms.string position="first" name="size" label="Size" value="{{ $context->valueOf('size') }}"
                width2="4" attribute="readonly" />
            <x-laraknife.forms.string position="last" name="created_at" label="Date"
                value="{{ $context->valueOf('created_at') }}" width2="4" attribute="readonly" />
        </x-laraknife.panels.edit>
    </form>
@endsection
