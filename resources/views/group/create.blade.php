@extends('layouts.backend')

@section('content')
    <form id="group-create" action="/group-store" method="POST">
        @csrf
        @method('PUT')
        <x-laraknife.panels.create title="{{ __('Creation of a Group') }}">
            <x-laraknife.forms.string position="alone" name="name" label="Name" value="{{ $context->valueOf('name') }}"
                width2="4" />
            <x-laraknife.forms.text position="alone" name="info" label="Info" value="{{ $context->valueOf('info') }}"
                width2="10" rows="2" />
         </x-laraknife.panels.create>
    </form>
@endsection
