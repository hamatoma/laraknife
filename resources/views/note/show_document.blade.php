@extends('layouts.backend')

@section('content')
    <form id="file-show" action="/note-show_document/{{ $context->model->id }}/{{ $mode }}" method="POST">
        @csrf
        @if ($mode === 'delete')
            @method('DELETE')
        @endif
        <x-laraknife.panels.show title="{{ __($mode !== 'delete' ? 'A File' : 'Deletion of a Document') }}"
            mode="{{ $mode }}">
            <x-laraknife.forms.string position="alone" name="title" label="Title" value="{{ $context->valueOf('title') }}"
                width2="10" attribute="readonly" />
            <x-laraknife.forms.text position="alone" name="description" label="Description"
                value="{{ $context->valueOf('description') }}" width2="10" attribute="readonly" rows="4" />
            <x-laraknife.forms.combobox position="first" name="visibility_scope" label="Visibility" :options="$optionsVisibility"
                width2="4" attribute="readonly" />
            <x-laraknife.forms.combobox position="last" name="filegroup_scope" label="Filegroup" :options="$optionsFilegroup"
                width2="4" attribute="readonly" />
            <x-laraknife.forms.combobox position="first" name="owner_id" label="Owner" :options="$optionsUser" width2="4"
                attribute="readonly" />
            <x-laraknife.forms.string position="last" name="filename" label="Filename"
                value="{{ $context->valueOf('filename') }}" width2="4" attribute="readonly" />
            <x-laraknife.forms.string position="first" name="size" label="Size" value="{{ $context->valueOf('size') }}"
                width2="4" attribute="readonly" rows="2" />
            <x-laraknife.forms.string position="last" name="created_at" label="Date"
                value="{{ $context->valueOf('created_at') }}" width2="4" attribute="readonly" />
        </x-laraknife.panels.show>
    </form>
@endsection
