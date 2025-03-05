@extends('layouts.backend')

@section('content')
    <form id="page-create" action="/page-store" method="POST">
        @csrf
        @method('PUT')
        <x-laraknife.panels.create title="{{ __('Creation of a Page') }}">
            <x-laraknife.forms.combobox position="first" name="pagetype_scope" label="Pagetype" :options="$optionsPagetype"
                width2="4" />
            <x-laraknife.forms.combobox position="last" name="markup_scope" label="Markup" :options="$optionsMarkup" width2="4" />
            <x-laraknife.forms.string position="first" name="title" label="Title"
                value="{{ $context->valueOf('title') }}" width2="4" />
            <x-laraknife.forms.string position="last" name="name" label="Name (URL)"
                value="{{ $context->valueOf('name') }}" width2="4" />
            <x-laraknife.forms.combobox position="alone" name="language_scope" label="Language" :options="$optionsLanguage"
                width2="4" />
            <x-laraknife.forms.text position="alone" name="contents" label="Contents"
                value="{{ $context->valueOf('contents') }}" width2="10" rows="10" />
            <x-laraknife.forms.text position="alone" name="info" label="Info" value="{{ $context->valueOf('info') }}"
                width2="10" rows="2" />
        </x-laraknife.panels.create>
    </form>
@endsection
