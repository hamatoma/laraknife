@extends('layouts.frontend')

@section('content')
    <form id="page-showcol4" action="/page-showcol4/{{ $context->model->id }}" method="POST">
        @csrf
        <x-laraknife.panels.text-area title="{{ $context->valueof('title') }}" prev="{{ $context->valueof('prev') }}"
            up="{{ $context->valueof('up') }}"  next="{{ $context->valueof('next') }}" audio="{{ $context->valueof('audio') }}">
            <div class="lkn-text">
                <div class="row">
                    <div class="col-md-3">
                        {!! $context->valueOf('text1') !!}
                    </div>
                    <div class="col-md-3">
                        {!! $context->valueOf('text2') !!}
                    </div>
                    <div class="col-md-3">
                        {!! $context->valueOf('text3') !!}
                    </div>
                    <div class="col-md-3">
                        {!! $context->valueOf('text4') !!}
                    </div>
                </div>
            </div>
        </x-laraknife.panels.text-area>
    </form>
@endsection
