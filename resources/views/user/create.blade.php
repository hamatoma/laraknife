@extends('layouts.backend')

@section('content')
<form id="user-create" action="/user-store" method="POST">
    @csrf
    @method('PUT')
    <x-laraknife.create-panel title="{{__('Creation of an User')}}">
        <x-laraknife.text position="first" name="name" label="Name" value="{{$context->valueOf('name')}}" width2="4" />
        <x-laraknife.text position="last" name="email" label="Email" value="{{$context->valueOf('email')}}" width2="4" />
        <x-laraknife.combobox position="alone" name="role_id" label="Role" :options="$roleOptions" class="lkn-autoupdate" width2="4" />
        <x-laraknife.password position="first" name="password" label="Password" width2="4" />
        <x-laraknife.password position="last" name="password_confirmation" label="Confirmation" width2="4" />
    </x-laraknife.create-panel>
</form>
@endsection
