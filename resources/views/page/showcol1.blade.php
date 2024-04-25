@extends('layouts.backend')

@section('content')
    <form id="page-show" action="/page-show/{{ $context->model->id }}" method="POST">
        @csrf
        <x-laraknife.panels.noform-text title="{{ $context->valueof('title') }}">
            <div class="row">
                {!! $context->valueOf('text') !!}
            </div>
        </x-laraknife.panels.noform-text>
        @if ($context->valueof('link') != null)
            <div class="row">
                <x-laraknife.forms.audio width1="5" width2="2" fileLink="{{ $context->valueof('link') }}" />
            </div>
        @endif
    </form>
@endsection
