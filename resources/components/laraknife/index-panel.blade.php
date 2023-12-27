@props(['title' => 'index-panel', 'mode' => 'delete'])
<div id="main-content" class="container mt-5">
    <x-laraknife.main-header title="{{ $title }}" />
    {{$slot}}
</div>