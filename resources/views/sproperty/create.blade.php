@extends('layouts.backend')

@section('content')
    <form id="sproperty-create" action="/sproperty-create" method="POST">
        @csrf
        @method('PUT')
        <div id="main-content" class="container mt-5">
            <x-laraknife.main-header title="{{ __('Creation of a Scoped Property') }}" />

            <x-laraknife.text position="first" name="id" label="Id" width2="4" />
            <x-laraknife.text position="last" name="scope" label="Scope" width2="4" />

            <x-laraknife.text position="first" name="name" label="Name" width2="4" />
            <x-laraknife.text position="last" name="shortname" label="Shortname" width2="4" />

            <x-laraknife.text position="first" name="order" label="Order" width2="4" value="9999" />
            <x-laraknife.text position="last" name="value" label="Value" width2="4" />

            <x-laraknife.bigtext position="alone" name="info" label="Info" width2="10" rows="4" />
            <x-laraknife.row-empty />
            <div class="row">
                <x-laraknife.btn-store width1="2" width2="4" />
                <x-laraknife.btn-cancel url="/sproperty-index" width1="2" width2="4" />
            </div>
            <x-laraknife.form-error/>
        </div>
    </form>
@endsection
