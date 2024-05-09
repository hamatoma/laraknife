@extends('layouts.backend')

@section('content')
    <form id="group-show" action="/group-show/{{ $context->model->id }}/{{ $mode }}" method="POST">
        @csrf
        @if ($mode === 'delete')
            @method('DELETE')
        @endif
        <x-laraknife.panels.show title="{{ __($mode !== 'delete' ? 'A Group' : 'Deletion of a Group') }}"
            mode="{{ $mode }}">
            <x-laraknife.forms.string position="alone" name="name" label="Name" value="{{ $context->valueOf('name') }}"
                width2="4" attribute="readonly" />
            <x-laraknife.forms.text position="alone" name="members" label="Members"
                value="{{ $context->valueOf('members', true) }}" width2="10" attribute="readonly" rows="2" />
            <x-laraknife.forms.text position="alone" name="info" label="Info" value="{{ $context->valueOf('info') }}"
                width2="10" attribute="readonly" rows="2" />
        </x-laraknife.panels.show>
    </form>
@endsection
