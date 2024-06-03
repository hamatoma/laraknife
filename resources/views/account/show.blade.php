@extends('layouts.backend')

@section('content')
    <form id="account-show" action="/account-show/{{ $context->model->id }}/{{ $mode }}" method="POST">
        @csrf
        @if($mode === 'delete')
        @method('DELETE')
        @endif
        <x-laraknife.panels.show title="{{ __($mode !== 'delete' ? 'A Account' : 'Deletion of a Account') }}" mode="{{$mode}}">
            <x-laraknife.forms.string position="first" name="id" label="Id" value="{{ $context->model->id }}" width2="4" attribute="readonly" />
            <x-laraknife.forms.combobox position="alone" name="mandator_id" label="Mandator" :options="$optionsMandator" width2="4" attribute="readonly"/>
            <x-laraknife.forms.text position="alone" name="info" label="Info" value="{{ $context->valueOf('info') }}" width2="4" attribute="readonly" rows="2" />
            <x-laraknife.forms.string position="alone" name="name" label="Name" value="{{ $context->valueOf('name') }}" width2="4" attribute="readonly" />
        </x-laraknife.panels.show>
    </form>
@endsection
