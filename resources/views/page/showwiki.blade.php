@extends('layouts.backend')

@section('content')
    <form id="page-showwiki" action="/page-editwiki/{{ $context->model->id }}" method="POST">
        @csrf
        <x-laraknife.panels.wiki-text title="{{ $context->valueof('title') }}">
            <div class="row">
                {!! $context->valueOf('text') !!}
            </div>
        </x-laraknife.panels.noform-text>
    </form>
@endsection
