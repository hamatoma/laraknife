@props(['title' => '', 'error' => '', 'class' => ''])
<div id="main-content" class="container mt-5">
    <x-laraknife.panels.common title="{{ $title }}" />
    <button class="lkn-change-button" name="btnSubmit" value="btnChange">{{ __("Change")}}</button> 
    <div class="lkn-text {{$class}}">
        {{ $slot }}
    </div>
</div>
</div>