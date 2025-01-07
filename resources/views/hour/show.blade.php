@extends('layouts.backend')

@section('content')
    <form id="hour-show" action="/hour-show/{{ $context->model->id }}/{{ $mode }}" method="POST">
        @csrf
        @if ($mode === 'delete')
            @method('DELETE')
        @endif
        <x-laraknife.panels.show title="{{ __($mode !== 'delete' ? 'A Hour' : 'Deletion of a Hour') }}"
            mode="{{ $mode }}">
            <x-laraknife.forms.combobox position="first" name="hourtype_scope" label="Hourtype" :options="$optionsHourtype" width2="4"
                attribute="readonly" />
            <x-laraknife.forms.combobox position="last" name="hourstate_scope" label="Hourstate" :options="$optionsHourstate"
                width2="4" attribute="readonly" />
            <x-laraknife.forms.string type="datetime-local" position="first" name="time" label="Time"
                value="{{ $context->valueOf('time') }}" width2="4" attribute="readonly" />
            <x-laraknife.forms.string position="last" name="duration" label="Duration" value="{{ $context->valueOf('duration') }}"
                width2="4" attribute="readonly" />
            <x-laraknife.forms.combobox position="alone" name="owner_id" label="Owner" :options="$optionsOwner" width2="4"
                attribute="readonly" />
            <x-laraknife.forms.text position="alone" name="description" label="Description"
                value="{{ $context->valueOf('description') }}" width2="10" attribute="readonly" rows="2" />
        </x-laraknife.panels.show>
    </form>
@endsection
