@extends('layouts.backend')

@section('content')
<form id="user-create" action="/user-create" method="POST">
    @csrf
    <x-laraknife.create-panel title="{{__('Creation of an User')}}" error="{{$error}}">
        <x-laraknife.text position="first" name="name" label="Name" value="{{$fields['name']}}" width2="4" />
        <x-laraknife.text position="last" name="email" label="Email" value="{{$fields['email']}}" width2="4" />
        <x-laraknife.combobox position="alone" name="role_id" label="Role" :options="$roleOptions" class="lkn-autoupdate" width2="4" />
        <x-laraknife.password position="first" name="password" label="Password" width2="4" />
        <x-laraknife.password position="last" name="password_confirmation" label="Confirmation" width2="4" />
    </x-laraknife.create-panel>
</form>
@endsection
