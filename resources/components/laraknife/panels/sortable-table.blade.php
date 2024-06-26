@props(['context', 'pagination'])
<div class="lkn-form-table">
    <x-laraknife.forms.form-error />
    <input name="_sortParams" id="_sortParams" type="hidden" value="{{ $context->valueOf('_sortParams') }}">
    <input type="hidden" name="pageIndex" id="_pageIndex" value="{{ $pagination->pageIndex }}">
    <div class="lkn-pagination-block">
        <ul class="pagination lkn-pagination">
            @php $list = $pagination->listItems();  @endphp
            @foreach ($list as $item)
                @if ($item['isGap'])
                    <li class="page-item">..</li>
                @else
                    <li @if ($item['active']) class="page-item active" @else class="page-item" @endif> <a
                            data-paging-index="{{ $item['no'] - 1 }}" class="page-link"
                            href="#{{ $item['no'] }}">{{ $item['no'] }}</a></li>
                @endif
            @endforeach
        </ul>
        @if($context->combobox() != null)
        @php 
        $name = $context->combobox()['name'];
        $attribute = $context->combobox()['attr'];
        $class = $context->combobox()['class'];
        $options = $context->combobox()['opt'];
        @endphp
        <select class="lkn-input left {{$class}}" id="fld_{{$name}}" name="{{$name}}" @if ($attribute === 'readonly') disabled @ else {{$attribute}} @endif>
            @foreach($options as $option)
            @php 
            $v = $option['value'];
            $a = $option['active'] ? ' selected' : '';
            $t = $option['text'];
            @endphp
            <option value="{{$v}}"{{$a}}>{{$t}}</option>
            @endforeach
        </select>
        @endif
        <div class="lkn-left-pagination-block">
            {{ __('Filtered') }}: {{ $pagination->filteredCount }} {{ __('from') }} {{ $pagination->totalCount }}
            &nbsp; &nbsp;
            <select id="_pageSize" name="pageSize" class="lkn-paging-text lkn-autoupdate">
                @foreach ([10, 20, 50, 100] as $size)
                    <option value="{{ $size }}"@if ($pagination->pageSize == $size) selected="selected" @endif>
                        {{ $size }}</option>
                @endforeach
                <option value="-1"@if ($pagination->pageSize == -1) selected="selected" @endif>__("All")</option>
            </select>
            {{ __('Lines per Page') }}
        </div>
    </div>
    <table class="sortable-table table table-striped lkn-table">
        {{ $slot }}
    </table>
</div>
