@extends('layouts.backend')

@section('content')
<form id="sproperty-create" action="/sproperty-store" method="POST">
    @csrf
    @method('PUT')
    <x-laraknife.create-panel title="{{__('Creation of a Scoped Property')}}">
        <x-laraknife.text position="first" name="id" label="Id" value="{{$context->valueOf('id')}}" width2="4" />
        <x-laraknife.text position="last" name="scope" label="Scope" value="{{$context->valueOf('scope')}}" width2="4" />

        <x-laraknife.text position="first" name="name" label="Name" value="{{$context->valueOf('name')}}" width2="4" />
        <x-laraknife.text position="last" name="shortname" label="Shortname" value="{{$context->valueOf('shortname')}}" width2="4" />

        <x-laraknife.text position="first" name="order" label="Order" value="{{$context->valueOf('order')}}" width2="4" />
        <x-laraknife.text position="last" name="value" label="Value" value="{{$context->valueOf('value')}}" width2="4" />

        <x-laraknife.bigtext position="alone" name="info" label="Info" value="{{$context->valueOf('info')}}" width2="10" rows="4" />
    </x-laraknife.create-panel>
</form>
@endsection
