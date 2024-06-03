@extends('layouts.backend')

@section('content')
    <form id="mandator-show" action="/mandator-show/{{ $context->model->id }}/{{ $mode }}" method="POST">
        @csrf
        @if($mode === 'delete')
        @method('DELETE')
        @endif
        <x-laraknife.panels.show title="{{ __($mode !== 'delete' ? 'A Mandator' : 'Deletion of a Mandator') }}" mode="{{$mode}}">
            <x-laraknife.forms.string position="first" name="id" label="Id" value="{{ $context->model->id }}" width2="4" attribute="readonly" />
            <x-laraknife.forms.combobox position="alone" name="group_id" label="Group" :options="$optionsGroup" width2="4" attribute="readonly"/>
            <x-laraknife.forms.text position="alone" name="info" label="Info" value="{{ $context->valueOf('info') }}" width2="4" attribute="readonly" rows="2" />
            <x-laraknife.forms.string position="alone" name="name" label="Name" value="{{ $context->valueOf('name') }}" width2="4" attribute="readonly" />
        </x-laraknife.panels.show>
    </form>
@endsection
