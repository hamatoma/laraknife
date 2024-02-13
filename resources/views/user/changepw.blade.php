@extends('layouts.backend')

@section('content')
<form id="user-editpassword" action="/user-editpassword/{{ $user->id }}" method="POST">
    @csrf
    <x-laraknife.panels.edit title="{{ __('Change Password of an User') }}">
        <x-laraknife.forms.string position="alone" name="name" label="Name" value="{{ $user->name }}" width2="4" attribute="readonly" />
        <x-laraknife.forms.password position="first" name="password" label="Password" width2="4" />
        <x-laraknife.forms.password position="last" name="password_confirmation" label="Confirmation" width2="4" />
     </x-laraknife.panels.edit>
</form>
@endsection
