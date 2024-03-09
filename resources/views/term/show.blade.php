@extends('layouts.backend')

@section('content')
    <form id="term-show" action="/term-show/{{ $context->model->id }}/{{ $mode }}" method="POST">
        @csrf
        @if ($mode === 'delete')
            @method('DELETE')
        @endif
        <x-laraknife.panels.show title="{{ __($mode !== 'delete' ? 'A Term' : 'Deletion of a Term') }}"
            mode="{{ $mode }}">
            <x-laraknife.forms.combobox position="first" name="visibility_scope" label="Visibility" :options="$optionsVisibility"
                width2="4" attribute="readonly" />
            <x-laraknife.forms.combobox position="last" name="owner_id" label="Owner" :options="$optionsOwner" width2="4"
                attribute="readonly" />
            <x-laraknife.forms.string type="datetime-local" position="first" name="term" label="Term" value="{{ $context->valueOf('term') }}"
                width2="4" attribute="readonly" />
            <x-laraknife.forms.string position="last" name="duration" label="Duration" value="{{ $context->valueOf('duration') }}"
                width2="4" attribute="readonly" />
            <x-laraknife.forms.string position="alone" name="title" label="Title" value="{{ $context->valueOf('title') }}"
                width2="10" attribute="readonly" />
            <x-laraknife.forms.text position="alone" name="description" label="Description"
                value="{{ $context->valueOf('description') }}" width2="10" attribute="readonly" rows="5" />
        </x-laraknife.panels.show>
    </form>
@endsection
