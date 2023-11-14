@extends('layouts.backend')

@section('content')
    <form id="user-index" action="/user-index" method="POST">
        @csrf
        <div id="main-content" class="container mt-5">
            <x-laraknife.main-header title="{{ __('Users') }}" />

                <!-- panel.filter -->
                <fieldset class="kn-filter">
                    <legend>{{ $legend }}</legend>
                        <x-laraknife.text position="first" name="id" label="Id" value="{{$fields['id']}}" width2="4" />
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
                          <th>{{__('Id')}}</th><th>{{__('Name')}}</th><th>{{__('Email')}}</th>
                          <th></th>
                      </tr>
                  </thead>
                  @foreach ($records as $user)
                  <tr>
                      <td><a href="/user-edit/{{$user->id}}">{{ __('Change')}}</a></td>
                      <td>{{$user->id}}</td>
                      <td>{{$user->name}}</td>
                      <td>{{$user->email}}</td>
                      <td><a href="/user-show/{{$user->id}}/delete">{{ __('Delete')}}</a></td>
                  </tr>
              @endforeach
                <tbody>
                  </tbody>
                </table>
            </div>
        </div>
    </form>
@endsection
