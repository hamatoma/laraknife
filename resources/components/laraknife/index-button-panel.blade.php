@props(['buttonType' => 'new'])
<div class="lkn-behind-filter">
    <div class="row">
        @if ($buttonType === 'new')
        <x-laraknife.btn-new width1="0" width2="12" />
        @endif
        {{$slot}}
    </div>
</div>