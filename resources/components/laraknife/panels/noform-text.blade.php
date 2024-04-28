@props(['title' => 'Edit', 'error' => '', 'class' => ''])
<div id="main-content" class="container mt-5">
    <x-laraknife.panels.common title="{{ $title }}" />
    <div class="lkn-text {{$class}}">
        {{ $slot }}
    </div>
</div>