@extends('layouts.backend')

@section('content')
<form id="user-edit" action="/user-update/{{ $user->id }}" method="POST">
    @csrf
    <x-laraknife.edit-panel title="{{ __('Change of an User') }}">
        <x-laraknife.text position="first" name="name" label="Name" value="{{ $user->name }}" width2="4" />
        <x-laraknife.text position="last" name="email" label="Email" value="{{ $user->email }}" width2="4" />
        <x-laraknife.combobox position="alone" name="role_id" label="Role" :options="$roleOptions" class="lkn-autoupdate" width2="4" />
     </x-laraknife.edit-panel>
</form>
@endsection
