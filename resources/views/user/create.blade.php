@extends('layouts.backend')

@section('content')
<form id="user-create" action="/user-store" method="POST">
    @csrf
    @method('PUT')
    <x-laraknife.panels.create title="{{__('Creation of an User')}}">
        <x-laraknife.forms.string position="first" name="name" label="Name" value="{{$context->valueOf('name')}}" width2="4" />
        <x-laraknife.forms.string position="last" name="email" label="Email" value="{{$context->valueOf('email')}}" width2="4" />
        <x-laraknife.forms.combobox position="alone" name="role_id" label="Role" :options="$roleOptions" class="width2="4" />
        <x-laraknife.forms.password position="first" name="password" label="Password" width2="4" />
        <x-laraknife.forms.password position="last" name="password_confirmation" label="Confirmation" width2="4" />
    </x-laraknife.panels.create>
</form>
@endsection
