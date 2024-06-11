@props(['buttonType' => 'new'])
    <div class="row lkn-behind-filter">
        @if ($buttonType === 'new')
        <x-laraknife.buttons.new width1="0" width2="12" />
        @endif
    </div>