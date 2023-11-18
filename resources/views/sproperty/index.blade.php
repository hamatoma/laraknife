@extends('layouts.backend')

@section('content')
    <form id="sproperty-index" action="/sproperty-index" method="POST">
        @csrf
        <div id="main-content" class="container mt-5">
            <x-laraknife.main-header title="{{ __('Scoped Properties') }}" />

                <!-- panel.filter -->
                <fieldset class="kn-filter">
                    <legend>{{ $legend }}</legend>
                        <x-laraknife.combobox position="first" name="scope" label="Scope" options="{!!$options!!}" width2="4" />
                        <x-laraknife.text position="last" name="text" label="Text" value="{{$fields['text']}}" width2="4" />
                    <div class="row">
                      <x-laraknife.btn-search width2="10" />
                    </div>
                  </fieldset>
                <div class="kn-behind-filter">
                  <div class="row">
                  <x-laraknife.btn-new width1="0" width2="12" />
                  </div>
                </div>
            <div class="kn-form-table">
                <table class="table table-striped kn-table-db">
                  <thead>
                      <tr>
                          <th></th>
                          <th>{{__('Id')}}</th><th>{{__('Scope')}}</th><th>{{__('Name')}}</th>
                          <th>{{__('Order')}}</th><th>{{__('Shortname')}}</th><th>{{__('Value')}}</th>
                          <th></th>
                      </tr>
                  </thead>
                  @foreach ($records as $sproperty)
                  <tr>
                      <td><x-laraknife.change-record module="sproperty" key="{{$sproperty->id}}" /></td>
                        <td>{{$sproperty->id}}</td>
                        <td>{{$sproperty->scope}}</td>
                        <td>{{$sproperty->name}}</td>
                        <td>{{$sproperty->order}}</td>
                        <td>{{$sproperty->shortname}}</td>
                        <td>{{$sproperty->value}}</td>
                        <td><x-laraknife.delete-record module="sproperty" key="{{$sproperty->id}}" /></td>
                  </tr>
              @endforeach
                <tbody>
                  </tbody>
                </table>
            </div>
        </div>
    </form>
@endsection
