@extends('layouts.backend')

@section('content')
    <form id="change-show" action="/change-show/{{ $context->model->id }}/{{ $mode }}" method="POST">
        @csrf
        @if($mode === 'delete')
        @method('DELETE')
        @endif
        <x-laraknife.panels.show title="{{ __($mode !== 'delete' ? 'A Change' : 'Deletion of a Change') }}" mode="{{$mode}}">
            <x-laraknife.forms.combobox position="first" name="changetype_scope" label="Changetype" :options="$optionsChangetype" width2="4" attribute="readonly"/>
            <x-laraknife.forms.combobox position="last" name="user_id" label="User" :options="$optionsUser" width2="4" attribute="readonly"/>
            <x-laraknife.forms.combobox position="first" name="module_id" label="Module" :options="$optionsModule" width2="4" attribute="readonly"/>
            <x-laraknife.forms.string position="last" name="reference_id" label="Reference" value="{{ $context->valueOf('reference_id') }}" width2="4" attribute="readonly"/>
            <x-laraknife.forms.text position="alone" name="current" label="Current" value="{{ $context->valueOf('current') }}" width2="10" attribute="readonly" rows="10" />
            <x-laraknife.forms.string position="alone" name="description" label="Description" value="{{ $context->valueOf('description') }}" width2="10" attribute="readonly" />
            <x-laraknife.forms.link position="alone" label="Link" reference="{{ $context->valueOf('link') }}" width2="4" attribute="readonly" />
        </x-laraknife.panels.show>
    </form>
@endsection
