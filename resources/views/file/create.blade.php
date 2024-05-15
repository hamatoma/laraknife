@extends('layouts.backend')

@section('content')
    <form enctype="multipart/form-data" id="file-create" action="/file-store" method="POST">
        @csrf
        @method('PUT')
        <x-laraknife.panels.create title="{{ __('Creation of a File') }}">
            <x-laraknife.forms.combobox position="first" name="filegroup_scope" label="Filegroup" :options="$optionsFilegroup"
                width2="4" />
            <x-laraknife.forms.combobox position="last" name="user_id" label="User" :options="$optionsUser" width2="4" />
            <x-laraknife.forms.string position="first" name="title" label="Title"
                value="{{ $context->valueOf('title') }}" width2="4" />
            <x-laraknife.forms.combobox position="last" name="visibility_scope" label="Visibility" :options="$optionsVisibility"
                width2="4" />
            <x-laraknife.forms.file position="first" name="file" label="File" width2="4" />
            <x-laraknife.forms.string position="last" name="filename" label="Filename"
                value="{{ $context->valueOf('filename') }}" width2="4"
                placeholder="Overwrites the upload name (if not empty)" />
            <x-laraknife.forms.text position="alone" name="description" label="Description"
                value="{{ $context->valueOf('description') }}" width2="10" rows="4" />
        </x-laraknife.panels.create>
    </form>
@endsection
