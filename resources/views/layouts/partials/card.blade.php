<?php /** @var \App\Http\Components\Presenter $presenter */ ?>
@extends('layouts.app')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title">@yield('card-title')</div>
            <div class="card-actions">@yield('card-actions')</div>
        </div>
        @yield('before-card-body')
        <div class="card-body card-block">
            @yield('card-body')
        </div>
        <div class="card-footer">
            @yield('card-footer')
        </div>
    </div>
@endsection