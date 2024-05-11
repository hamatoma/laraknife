@props(['title' => 'Edit', 'error' => '', 'class' => '', 'prev' => '', 'next' => '', 'up' => '', 'audio' => ''])
<div id="main-content" class="container mt-5 lkn-frame background">
    <div id="text-header-frame" class="lkn-header-frame">
        <h1>{{ $title }}</h1>
    </div>
    <div id="text-frame-panel" class="lkn-text-frame">
        {{ $slot }}
    </div>
    <div class="lkn-arrows">
        @if ($prev !== '')
            <div class="lkn-float-left lkn-nav-button">
                <a href="{{ $prev }}"> <i class="bi bi-arrow-left-circle-fill"></i> {{ __('Back') }}</a>
            </div>
        @endif
        @if ($next !== '')
        <div class="lkn-float-right lkn-nav-button">
            <a href="{{ $next }}">{{ __('Next') }} <i class="bi bi-arrow-right-circle-fill"></i></a>
        </div>
        @endif
        @if ($up !== '')
            <div class="lkn-center lkn-nav-button">
                <a href="{{ $up }}"> <i class="bi bi-arrow-up-circle-fill"></i></a>
            </div>
        @endif
    </div>
    @if ($audio !== '')
        <div>
            <x-laraknife.forms.audio-raw fileLink="{{ $audio }}" class="lkn-expand100" />
        </div>
    @endif
</div>
