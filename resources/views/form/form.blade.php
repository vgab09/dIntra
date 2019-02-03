<?php /** @var \App\Http\Components\FormHelper\FormHelper $formHelper */ ?>
@extends('layouts.app')
@section('content')
    <div class="card">
        <div class="card-header">
            {{$formHelper->getTitle()}}
        </div>
        {!! $formHelper->open() !!}
        <div class="card-body card-block">

            @foreach($formHelper->getFormItems() as $fieldName => $field)
                {!! $field->render() !!}
            @endforeach
        </div>
        <div class="card-footer">
            {!! Form::submit('Mentés',['class'=>'btn btn-primary']); !!}
        </div>
        {!! $formHelper->close() !!}
    </div>
@endsection