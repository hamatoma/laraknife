@props(['title' => 'Edit', 'error' => '', 'class' => ''])
<div id="main-content" class="container mt-5 lkn-frame background">
    <div id="text-header-frame" class="lkn-header-frame">
        <h1>{{ $title }}</h1>
    </div>
    <div id="text-frame-panel" class="lkn-text-frame">
             {{ $slot }}
    </div>
</div>
