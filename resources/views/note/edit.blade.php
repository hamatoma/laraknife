@extends('layouts.backend')

@section('content')
    <form id="note-edit" action="/note-update/{{ $context->model->id }}" method="POST">
        @csrf
        <x-laraknife.panels.standard title="{{ __('Change of a Note') }}" fieldset="false">
            <x-laraknife.layout.nav-tabs :info="$navTabsInfo" fieldset="true">
                <x-laraknife.forms.combobox position="first" name="category_scope" label="Category" :options="$optionsCategory"
                    value="{{ $context->valueOf('category_scope') }}" width2="4" />
                <x-laraknife.forms.combobox position="last" name="notestatus_scope" label="Status" :options="$optionsNotestatus"
                    width2="4" />
                <x-laraknife.forms.string position="first" name="title" label="Title"
                    value="{{ $context->valueOf('title') }}" width2="4" />
                <x-laraknife.forms.combobox position="last" name="visibility_scope" label="Visibility" :options="$optionsVisibility"
                    width2="4" />
                <x-laraknife.forms.text position="alone" name="body" label="Body"
                    value="{{ $context->valueOf('body') }}" width2="10" rows="10" />
            </x-laraknife.layout.nav-tabs>
            </x-laraknife.panels.edit>
    </form>
@endsection
