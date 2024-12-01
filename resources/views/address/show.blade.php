@extends('layouts.backend')

@section('content')
    <form id="address-show" action="/address-show/{{ $context->model->id }}/{{ $mode }}" method="POST">
        @csrf
        @if($mode === 'delete')
        @method('DELETE')
        @endif
        <x-laraknife.panels.show title="{{ __($mode !== 'delete' ? 'A Address' : 'Deletion of a Address') }}" mode="{{$mode}}">
            <x-laraknife.forms.combobox position="first" name="addresstype_scope" label="Addresstype" :options="$optionsAddresstype" width2="4" attribute="readonly"/>
            <x-laraknife.forms.combobox position="last" name="owner_id" label="Owner" :options="$optionsOwner" width2="4" attribute="readonly"/>
            <x-laraknife.forms.string position="first" name="name" label="Name" value="{{ $context->valueOf('name') }}" width2="4" attribute="readonly" />
            <x-laraknife.forms.string position="last" name="priority" label="Priority" value="{{ $context->valueOf('priority') }}" width2="4" attribute="readonly" />
            <x-laraknife.forms.text position="alone" name="info" label="Info" value="{{ $context->valueOf('info') }}" width2="10" rows="2" attribute="readonly" rows="2" />
        </x-laraknife.panels.show>
    </form>
@endsection
