<?php /** @var \App\Http\Components\FormHelper\FormHelper $formHelper */ ?>

@extends('layouts.partials.card')

@section('card-title')
    @if($formHelper->hasIcon())
        <i class="{{$formHelper->getIconClass()}}"></i>
    @endif
    {{$formHelper->getTitle()}}
@endsection

@section('before-card-body')
    {!! $formHelper->open() !!}
@endsection

@section('card-body')
    @foreach($formHelper->getFormItems() as $fieldName => $field)
        {!! $field->render() !!}
    @endforeach
@endsection

@section('card-footer')
    {!! $formHelper->renderSubmit() !!}
@endsection