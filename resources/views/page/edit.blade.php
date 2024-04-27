@extends('layouts.backend')

@section('content')
    <form id="page-edit" action="/page-edit/{{ $context->model->id }}" method="POST">
        @csrf
        <x-laraknife.panels.edit title="{{ __('Change of a Page') }}">
            <x-laraknife.forms.combobox position="first" name="pagetype_scope" label="Pagetype" :options="$optionsPagetype" width2="4"
                attribute="readonly" />
            <x-laraknife.forms.combobox position="last" name="markup_scope" label="Markup" :options="$optionsMarkup" width2="4"
                attribute="readonly" />
            <x-laraknife.forms.string type="number" position="first" name="columns" label="Columns"
                value="{{ $context->valueOf('columns') }}" width2="4" />
            <x-laraknife.forms.string type="number" position="last" name="order" label="Order"
                value="{{ $context->valueOf('order') }}" width2="4" />
            <x-laraknife.forms.combobox position="alone" name="language_scope" label="Language" :options="$optionsLanguage"
                width2="4" attribute="readonly" />
            <x-laraknife.forms.string position="first" name="title" label="Title"
                value="{{ $context->valueOf('title') }}" width2="4" />
            <x-laraknife.forms.string position="last" name="name" label="Name (URL)"
                value="{{ $context->valueOf('name') }}" width2="4" />
            <x-laraknife.forms.text position="alone" name="contents" label="Contents"
                value="{{ $context->valueOf('contents') }}" width2="10" rows="10" />
            <x-laraknife.forms.text position="alone" name="info" label="Info" value="{{ $context->valueOf('info') }}"
                width2="10" rows="2" />
            <x-laraknife.forms.file-protected position="first" name="file" fieldId="{{ $context->model->audio_id }}"
                label="Audio" width2="4" />
            <x-laraknife.buttons.button position="last" name="btnPreview" label="Preview" />
        </x-laraknife.panels.edit>
        @if (!empty($context->valueOf('preview')))
            <x-laraknife.panels.noform title="{{ __('Preview') }}">
                <div class="lkn-text">{!! $context->valueOf('preview') !!}
                </div>
            </x-laraknife.panels.noform>
        @endif
    </form>
@endsection
