@extends('layouts.backend')

@section('content')
<form id="sproperty-create" action="/sproperty-create" method="POST">
    @csrf
    <x-laraknife.create-panel title="{{__('Creation of a Scoped Property')}}" error="{{$error}}">
        <x-laraknife.text position="first" name="id" label="Id" value="{{$fields['id']}}" width2="4" />
        <x-laraknife.text position="last" name="scope" label="Scope" value="{{$fields['scope']}}" width2="4" />

        <x-laraknife.text position="first" name="name" label="Name" value="{{$fields['name']}}" width2="4" />
        <x-laraknife.text position="last" name="shortname" label="Shortname" value="{{$fields['shortname']}}" width2="4" />

        <x-laraknife.text position="first" name="order" label="Order" value="{{$fields['order']}}" width2="4" />
        <x-laraknife.text position="last" name="value" label="Value" value="{{$fields['value']}}" width2="4" />

        <x-laraknife.bigtext position="alone" name="info" label="Info" value="{{$fields['info']}}" width2="10" rows="4" />
    </x-laraknife.create-panel>
</form>
@endsection
