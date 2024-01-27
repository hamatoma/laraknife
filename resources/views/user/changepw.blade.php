@extends('layouts.backend')

@section('content')
<form id="user-editpassword" action="/user-editpassword/{{ $user->id }}" method="POST">
    @csrf
    <x-laraknife.edit-panel title="{{ __('Change Password of an User') }}" error="{{$error}}">
        <x-laraknife.text position="alone" name="name" label="Name" value="{{ $user->name }}" width2="4" attribute="readonly" />
        <x-laraknife.password position="first" name="password" label="Password" width2="4" />
        <x-laraknife.password position="last" name="password_confirmation" label="Confirmation" width2="4" />
     </x-laraknife.edit-panel>
</form>
@endsection
