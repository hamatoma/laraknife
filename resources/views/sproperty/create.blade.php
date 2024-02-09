@extends('layouts.backend')

@section('content')
<form id="sproperty-create" action="/sproperty-store" method="POST">
    @csrf
    @method('PUT')
    <x-laraknife.panels.create title="{{__('Creation of a Scoped Property')}}">
        <x-laraknife.forms.text position="first" name="id" label="Id" value="{{$context->valueOf('id')}}" width2="4" />
        <x-laraknife.forms.text position="last" name="scope" label="Scope" value="{{$context->valueOf('scope')}}" width2="4" />

        <x-laraknife.forms.text position="first" name="name" label="Name" value="{{$context->valueOf('name')}}" width2="4" />
        <x-laraknife.forms.text position="last" name="shortname" label="Shortname" value="{{$context->valueOf('shortname')}}" width2="4" />

        <x-laraknife.forms.text position="first" name="order" label="Order" value="{{$context->valueOf('order')}}" width2="4" />
        <x-laraknife.forms.text position="last" name="value" label="Value" value="{{$context->valueOf('value')}}" width2="4" />

        <x-laraknife.forms.bigtext position="alone" name="info" label="Info" value="{{$context->valueOf('info')}}" width2="10" rows="4" />
    </x-laraknife.panels.create>
</form>
@endsection
