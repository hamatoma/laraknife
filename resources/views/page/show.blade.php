@extends('layouts.backend')

@section('content')
    <form id="page-show" action="/page-show/{{ $context->model->id }}/{{ $mode }}" method="POST">
        @csrf
        @if($mode === 'delete')
        @method('DELETE')
        @endif
        <x-laraknife.panels.show title="{{ __($mode !== 'delete' ? 'An Page' : 'Deletion of an Page') }}" mode="{{$mode}}">
            <x-laraknife.forms.combobox position="first" name="pagetype_scope" label="Pagetype" :options="$optionsPagetype" width2="4" attribute="readonly"/>
            <x-laraknife.forms.combobox position="last" name="markup_scope" label="Markup" :options="$optionsMarkup" width2="4" attribute="readonly"/>
            <x-laraknife.forms.string position="alone" name="title" label="Title" value="{{ $context->valueOf('title') }}" width2="10" attribute="readonly" />
            <x-laraknife.forms.text position="alone" name="contents" label="Contents" value="{{ $context->valueOf('contents') }}" width2="10" attribute="readonly" rows="5" />
            <x-laraknife.forms.text position="alone" name="info" label="Info" value="{{ $context->valueOf('info') }}" width2="10" attribute="readonly" rows="2" />
        </x-laraknife.panels.show>
    </form>
@endsection
