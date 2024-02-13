@extends('layouts.backend')

@section('content')
    <form id="menuitem-show" action="/menuitem-show/{{ $context->model->id }}/{{ $mode }}" method="POST">
        @csrf
        @if($mode === 'delete')
        @method('DELETE')
        @endif
        <x-laraknife.panels.show title="{{ __($mode !== 'delete' ? 'A Menu' : 'Deletion of a Menu Item') }}" mode="{{$mode}}">
            <x-laraknife.forms.text position="first" name="name" label="Name" value="{{ $context->valueOf('name') }}" width2="4" attribute="readonly" />
            <x-laraknife.forms.text position="last" name="label" label="Label" value="{{ $context->valueOf('label') }}" width2="4" attribute="readonly" />
            <x-laraknife.forms.text position="first" name="icon" label="Icon" value="{{ $context->valueOf('icon') }}" width2="4" attribute="readonly" />
            <x-laraknife.forms.text position="last" name="section" label="Section" value="{{ $context->valueOf('section') }}" width2="4" attribute="readonly" />
            <x-laraknife.forms.text position="alone" name="link" label="Link" value="{{ $context->valueOf('link') }}" width2="10" attribute="readonly" />
        </x-laraknife.panels.show>
    </form>
@endsection
