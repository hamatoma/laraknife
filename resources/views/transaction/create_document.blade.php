@extends('layouts.backend')

@section('content')
    <form enctype="multipart/form-data" id="transaction-create_document"
        action="/transaction-store_document/{{ $transaction->id }}" method="POST">
        @csrf
        @method('PUT')
        <x-laraknife.panels.standard title="{{ __('Creation of a Document') }}" fieldset="false">
            <x-laraknife.layout.nav-tabs :info="$navTabsInfo" fieldset="true">
                <x-laraknife.forms.combobox position="first" name="filegroup_scope" label="Filegroup" :options="$optionsFilegroup"
                    width2="4" />
                <x-laraknife.forms.combobox position="last" name="visibility_scope" label="Visibility" :options="$optionsVisibility"
                    width2="4" />
                <x-laraknife.forms.string position="alone" name="title" label="Title"
                    value="{{ $context->valueOf('title') }}" width2="10" />
                <x-laraknife.forms.file position="first" name="file" label="File" width2="4" />
                <x-laraknife.forms.string position="last" name="filename" label="Filename"
                    value="{{ $context->valueOf('filename') }}" width2="4"
                    placeholder="{{ __('Overwrites the upload name (if not empty)') }}" />
                <x-laraknife.forms.text position="alone" name="description" label="Description"
                    value="{{ $context->valueOf('description') }}" width2="10" rows="4" />
            </x-laraknife.layout.nav-tabs>
        </x-laraknife.panels.standard>
    </form>
@endsection
