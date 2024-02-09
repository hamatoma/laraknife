@extends('layouts.backend')

@section('content')
    <form id="menu-edit" action="/menu-update/{{ $context->model->id  }}" method="POST">
        @csrf
        <x-laraknife.panels.edit title="{{ __('Change of a Menu') }}">
        <x-laraknife.forms.text position="first" name="name" label="Name" value="{{ $context->valueOf('name') }}" width2="4" />
        <x-laraknife.forms.text position="last" name="label" label="Label" value="{{ $context->valueOf('label') }}" width2="4" />
        <x-laraknife.forms.text position="first" name="icon" label="Icon" value="{{ $context->valueOf('icon') }}" width2="4" />
        <x-laraknife.forms.text position="last" name="section" label="Section" value="{{ $context->valueOf('section') }}" width2="4" />
        <x-laraknife.forms.text position="alone" name="link" label="Link" value="{{ $context->valueOf('link') }}" width2="10" />
        </x-laraknife.panels.edit>
    </form>
@endsection
