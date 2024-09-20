@extends('layouts.frontend')

@section('content')
    <form id="page-showcol1" action="/page-showpretty/{{ $context->model->id }}" method="POST">
        @csrf
        <x-laraknife.panels.text-area title="{{ $context->valueof('title') }}" prev="{{ $context->valueof('prev') }}"
            up="{{ $context->valueof('up') }}"  next="{{ $context->valueof('next') }}" audio="{{ $context->valueof('audio') }}">
            <div class="lkn-text">
                {!! $context->valueOf('text1') !!}
            </div>
        </x-laraknife.panels.text-area>
    </form>
@endsection
