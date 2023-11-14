@extends('layouts.backend')

@section('content')
    <form id="user-edit" action="/user-update/{{ $user->id }}" method="POST">
        @csrf
        <div id="main-content" class="container mt-5">
            <x-laraknife.main-header title="{{ __('Change of an User') }}" />

            <x-laraknife.text position="alone" name="id" label="Id" value="{{ $user->id }}" width2="4" attribute="readonly" />
            <x-laraknife.text position="first" name="name" label="Name" value="{{ $user->name }}" width2="4" />
            <x-laraknife.text position="last" name="email" label="Email" value="{{ $user->email }}" width2="4" />

            <x-laraknife.password position="first" name="password" label="Password" width2="4" />
            <x-laraknife.password position="last" name="repetition" label="Repetition" width2="4" />
            <x-laraknife.row-empty />
            <div class="row">
                <x-laraknife.btn-store width1="2" width2="4" />
                <x-laraknife.btn-cancel width1="2" width2="4" />
            </div>
            <x-laraknife.form-error />
    </form>
    </div>
@endsection
