@extends('layouts.backend')

@section('content')
    <form enctype="multipart/form-data" id="file-exchange" action="/file-updatefile/{{ $context->model->id  }}" method="POST">
        @method('PUT')
        @csrf
        <x-laraknife.panels.edit title="{{ __('Exchange of a File') }}">
            <x-laraknife.forms.string position="alone" name="title" label="Title" value="{{ $context->valueOf('title') }}" width2="10" attribute="readonly" />
            <x-laraknife.forms.text position="alone" name="description" label="Description" value="{{ $context->valueOf('description') }}" width2="10" rows="4" attribute="readonly"/>
            <x-laraknife.forms.file position="first" name="file" label="File" width2="4" rows="4" />
        </x-laraknife.panels.edit>
    </form>
@endsection
