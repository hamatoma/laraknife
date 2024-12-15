@extends('layouts.backend')

@section('content')
    <form id="location-show" action="/location-show/{{ $context->model->id }}/{{ $mode }}" method="POST">
        @csrf
        @if ($mode === 'delete')
            @method('DELETE')
        @endif
        <x-laraknife.panels.show title="{{ __($mode !== 'delete' ? 'A Location' : 'Deletion of a Location') }}"
            mode="{{ $mode }}">
            <x-laraknife.forms.string position="first" name="country" label="Country" value="{{ $context->valueOf('country') }}"
                width2="4" attribute="readonly" />
            <x-laraknife.forms.combobox position="last" name="person_id" label="Person" :options="$optionsPerson" width2="4"
                attribute="readonly" />
            <x-laraknife.forms.string position="first" name="zip" label="Zip" value="{{ $context->valueOf('zip') }}"
                width2="4" attribute="readonly" />
            <x-laraknife.forms.string position="last" name="city" label="City" value="{{ $context->valueOf('city') }}"
                width2="4" attribute="readonly" />
            <x-laraknife.forms.string position="first" name="street" label="Street"
                value="{{ $context->valueOf('street') }}" width2="4" attribute="readonly" />
            <x-laraknife.forms.string position="last" name="additional" label="Additional"
                value="{{ $context->valueOf('additional') }}" width2="4" attribute="readonly" />
            <x-laraknife.forms.text position="first" name="info" label="Info"
                value="{{ $context->valueOf('info') }}" width2="4" rows="2" attribute="readonly" />
            <x-laraknife.forms.string position="last" name="priority" label="Priority"
                value="{{ $context->valueOf('priority') }}" width2="4" attribute="readonly" />
        </x-laraknife.panels.show>
    </form>
@endsection
