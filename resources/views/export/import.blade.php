@extends('layouts.backend')

@section('content')
    <form enctype="multipart/form-data" id="export-import" action="/export-import" method="POST">
        @csrf
        <x-laraknife.panels.create title="{{ __('Import') }}">
            <input type="hidden" name="filename" value="{{ $context->valueOf('filename') }}">
            <x-laraknife.forms.file position="first" name="file" label="File" width2="4" />
            <x-laraknife.buttons.button-position position="last" name="btnUpload" label="Analysis" />
            <x-laraknife.forms.text position="alone" name="info" label="Info"
                value="{{ $context->valueOf('info') }}" width2="10" rows="5" attribute="readonly"/>
        </x-laraknife.panels.create>
    </form>
@endsection
