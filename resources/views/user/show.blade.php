@extends('layouts.backend')

@section('content')
    <form id="user-show" action="/user-show/{{ $user->id }}/delete" method="POST">
        @csrf
        @method('DELETE')
        <div id="main-content" class="container mt-5">
            @if($mode === 'delete')
            <x-laraknife.main-header title="{{ __('Deletion of an User') }}" />
            @else
            <x-laraknife.main-header title="{{ __('Displaying an User') }}" />
            @endif
            <x-laraknife.text position="alone" name="id" label="Id" width2="4" attribute="readonly" />

            <x-laraknife.text position="first" name="name" label="Name" width2="4" attribute="readonly" />
            <x-laraknife.text position="last" name="email" label="Email" width2="4" attribute="readonly" />
            <x-laraknife.row-empty />
            <div class="row">
                @if($mode === 'delete')
                <x-laraknife.btn-delete width1="2" width2="4" />
                <x-laraknife.btn-cancel width1="2" width2="4" />
                @else
                <x-laraknife.btn-cancel width1="8" width2="4" />
                @endif
            </div>
            <x-laraknife.form-error />
    </form>
    </div>
@endsection
