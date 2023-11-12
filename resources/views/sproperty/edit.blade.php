@extends('layouts.backend')

@section('content')
    <form id="sproperty-edit" action="/sproperty-update/{{ $sproperty->id }}" method="POST">
        @csrf
        <div id="main-content" class="container mt-5">
            <x-laraknife.main-header title="{{ __('Change of a Scoped Property') }}" />

            <x-laraknife.text position="first" name="id" label="Id" value="{{ $sproperty->id }}" width2="4"
                attribute="readonly" />
            <x-laraknife.text position="last" name="scope" label="Scope" value="{{ $sproperty->scope }}" width2="4"
                attribute="readonly" />

            <x-laraknife.text position="first" name="name" label="Name" value="{{ $sproperty->name }}"
                width2="4" />
            <x-laraknife.text position="last" name="shortname" label="Shortname" value="{{ $sproperty->shortname }}"
                width2="4" />

            <x-laraknife.text position="first" name="order" label="Order" width2="4"
                value="{{ $sproperty->order }}" />
            <x-laraknife.text position="last" name="value" label="Value" width2="4"
                value="{{ $sproperty->value }}" />

            <x-laraknife.bigtext position="alone" name="info" label="Info" value="{{ $sproperty->info }}"
                width2="10" rows="4" />

            <x-laraknife.row-empty />
            <div class="row">
                <x-laraknife.btn-store width1="2" width2="4" />
                <x-laraknife.btn-cancel width1="2" width2="4" />
            </div>
            <x-laraknife.form-error />
    </form>
    </div>
@endsection
