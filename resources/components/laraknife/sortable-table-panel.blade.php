@props(['fields', 'pagination'])
<div class="lkn-form-table">
    <x-laraknife.hidden-button />
    <x-laraknife.sortable-table value="{{ $fields['_sortParams'] }}" />
    <input name="_sortParams" id="_sortParams" type="hidden" value="{{ $fields['_sortParams'] }}">
    <input type="hidden" name="pageIndex" id="_pageIndex" value="$this->pageIndex" />
    <div class="lkn-pagination-block">
        <ul class="pagination lkn-pagination">
            @foreach ($pagination->listItems() as $item)
                @if ($item['isGap'])
                    <li class="page-item">..</li>
                @else
                    <li @if ($item['active']) class="page-item active" @else class="page-item" @endif> <a
                            data-paging-index="{{ $item['no'] - 1 }}" class="page-link"
                            href="#{{ $item['no'] }}">{{ $item['no'] }}</a></li>
                @endif
            @endforeach
        </ul>
        <div class="lkn-left-pagination-block">
            {{ __('Filtered') }}: {{ $pagination->filteredCount }} {{ __('from') }} {{ $pagination->totalCount }}
            &nbsp; &nbsp;
            <select id="_pageSize" name="pageSize" class="lkn-paging-text lkn-autoupdate">
                @foreach ([10, 20, 50, 100] as $size)
                    <option value="$size"@if ($pagination->pageSize == $size) selected="selected" @endif>
                        {{ $size }}</option>
                @endforeach
                <option value="-1"@if ($pagination->pageSize == -1) selected="selected" @endif>__("All")</option>
            </select>
            {{ __('Lines per Page') }}
        </div>
    </div>
    <table class="sortable-table table table-striped lkn-table-db">
        {{ $slot }}
    </table>
</div>
