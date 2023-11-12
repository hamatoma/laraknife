@extends('layouts.backend')

@section('content')
    <form id="sproperty-delete" action="/sproperty-delete/{{ $sproperty->id }}" method="POST">
        @csrf
        @method('DELETE')
        <div id="main-content" class="container mt-5">
            <x-laraknife.main-header title="{{ __('Deletion of a Scoped Property') }}" />

            <x-laraknife.text position="first" name="id" label="Id" value="{{ $sproperty->id }}" width2="4"
                attribute="readonly" />
            <x-laraknife.text position="last" name="scope" label="Scope" value="{{ $sproperty->scope }}" width2="4"
                attribute="readonly" />

            <x-laraknife.text position="first" name="name" label="Name" value="{{ $sproperty->name }}"
                width2="4" attribute="readonly"/>
            <x-laraknife.text position="last" name="shortname" label="Shortname" value="{{ $sproperty->shortname }}"
                width2="4" attribute="readonly"/>

            <x-laraknife.text position="first" name="order" label="Order" width2="4"
                value="{{ $sproperty->order }}" attribute="readonly"/>
            <x-laraknife.text position="last" name="value" label="Value" width2="4"
                value="{{ $sproperty->value }}" attribute="readonly"/>

            <x-laraknife.bigtext position="alone" name="info" label="Info" value="{{ $sproperty->info }}"
                width2="10" rows="4" attribute="readonly"/>

            <x-laraknife.row-empty />
            <div class="row">
                <x-laraknife.btn-delete width1="2" width2="4" />
                <x-laraknife.btn-cancel width1="2" width2="4" />
            </div>
            <x-laraknife.form-error />
    </form>
    </div>
@endsection
