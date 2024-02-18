@extends('layouts.backend')

@section('content')
    <form id="menuitem-edit" action="/menuitem-update/{{ $context->model->id }}" method="POST">
        @csrf
        <x-laraknife.panels.edit title="{{ __('Change of a Menu Item') }}">
            <x-laraknife.forms.string position="first" name="name" label="Name" value="{{ $context->valueOf('name') }}"
                width2="4" />
            <x-laraknife.forms.string position="last" name="label" label="Label" value="{{ $context->valueOf('label') }}"
                width2="4" />
            <x-laraknife.forms.string position="first" name="icon" label="Icon" value="{{ $context->valueOf('icon') }}"
                width2="4" />
            <x-laraknife.forms.string position="last" name="section" label="Section"
                value="{{ $context->valueOf('section') }}" width2="4" />
            <x-laraknife.forms.string position="alone" name="link" label="Link" value="{{ $context->valueOf('link') }}"
                width2="10" />
            <x-laraknife.layout.cell position="first" content="Icons" width="2" />
            <div class="col-md-2"><a href="https://icons.getbootstrap.com"
                    target="_blank">https://icons.getbootstrap.com</a></div>
            <x-laraknife.forms.set-position position="last" />
        </x-laraknife.panels.edit>
    </form>
@endsection
