@extends('layouts.backend')

@section('content')
    <form id="note-show" action="/note-show/{{ $context->model->id }}/{{ $mode }}" method="POST">
        @csrf
        @if ($mode === 'delete')
            @method('DELETE')
        @endif
        <x-laraknife.panels.show title="{{ __($mode !== 'delete' ? 'A Note' : 'Deletion of a Note') }}"
            mode="{{ $mode }}">
            <x-laraknife.forms.combobox position="first" name="category_scope" label="Category" :options="$optionsCategory"
                value="{{ $context->valueOf('category_scope') }}" width2="4" attribute="readonly" />
            <x-laraknife.forms.combobox position="last" name="notestatus_scope" label="Status" :options="$optionsNotestatus"
                width2="4" attribute="readonly" />
            <x-laraknife.forms.string position="first" name="id" label="Id" value="{{ $context->valueOf('id') }}"
                width2="4" attribute="readonly" />
            <x-laraknife.forms.combobox position="last" name="user_id" label="User" :options="$optionsUser" width2="4"
                attribute="readonly" />
            <x-laraknife.forms.string position="first" name="title" label="Title"
                value="{{ $context->valueOf('title') }}" width2="4" attribute="readonly" />
            <x-laraknife.forms.combobox position="last" name="visibility_scope" label="Visibility" :options="$optionsVisibility"
                width2="4" attribute="readonly" />
            <x-laraknife.forms.text position="alone" name="body" label="Body" value="{{ $context->valueOf('body') }}"
                width2="10" attribute="readonly" rows="2" />
        </x-laraknife.panels.show>
    </form>
@endsection
