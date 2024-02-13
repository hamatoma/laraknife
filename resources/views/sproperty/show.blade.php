@extends('layouts.backend')

@section('content')
<form id="sproperty-show" action="/sproperty-show/{{ $sproperty->id }}/{{ $mode }}" method="POST">
    @csrf
    @if ($mode === 'delete')
    @method('DELETE')
    @endif
    <x-laraknife.panels.show title="{{ __($mode === 'delete' ? 'Deletion of a Scoped Property' : 'A Scoped Property') }}" mode="{{$mode}}">
        <x-laraknife.forms.string position="first" name="id" label="Id" value="{{ $sproperty->id }}" width2="4"
            attribute="readonly" />
        <x-laraknife.forms.string position="last" name="scope" label="Scope" value="{{ $sproperty->scope }}" width2="4"
            attribute="readonly" />

        <x-laraknife.forms.string position="first" name="name" label="Name" value="{{ $sproperty->name }}"
            width2="4" attribute="readonly"/>
        <x-laraknife.forms.string position="last" name="shortname" label="Shortname" value="{{ $sproperty->shortname }}"
            width2="4" attribute="readonly"/>

        <x-laraknife.forms.string position="first" name="order" label="Order" width2="4"
            value="{{ $sproperty->order }}" attribute="readonly"/>
        <x-laraknife.forms.string position="last" name="value" label="Value" width2="4"
            value="{{ $sproperty->value }}" attribute="readonly"/>

        <x-laraknife.forms.text position="alone" name="info" label="Info" value="{{ $sproperty->info }}"
            width2="10" rows="4" attribute="readonly"/>

    </x-laraknife.panels.create>
</form>
@endsection
