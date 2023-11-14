@extends('layouts.backend')

@section('content')
    <form id="user-create" action="/user-create" method="POST">
        @csrf
        @method('PUT')
        <div id="main-content" class="container mt-5">
            <x-laraknife.main-header title="{{ __('Creation of an User') }}" />

            <x-laraknife.text position="first" name="name" label="Name" width2="4" />
            <x-laraknife.text position="last" name="email" label="Email" width2="4" />

            <x-laraknife.password position="first" name="password" label="Password" width2="4" />
            <x-laraknife.password position="last" name="repetition" label="Repetition" width2="4" />
            <x-laraknife.row-empty />
            <div class="row">
                <x-laraknife.btn-store width1="2" width2="4" />
                <x-laraknife.btn-cancel url="/user-index" width1="2" width2="4" />
            </div>
            <x-laraknife.form-error/>
        </div>
    </form>
@endsection
