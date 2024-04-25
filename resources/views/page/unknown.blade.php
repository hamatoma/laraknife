@extends('layouts.backend')

@section('content')
    <form id="page-unknown" action="/page-index" method="POST">
        @csrf
        <x-laraknife.panels.noform-text title="{{ __('Unknown page request') }}">
            <x-laraknife.forms.const-text text=" {{ $context->valueOf('text') }}" />
        </x-laraknife.panels.noform-text>
    </form>
@endsection
