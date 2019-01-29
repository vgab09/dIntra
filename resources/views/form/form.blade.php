<?php /** @var \App\Http\Components\FormHelper\FormHelper $formHelper */ ?>
@extends('layouts.app')
@section('page_title')
{{$formHelper->getTitle()}}
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            {{$formHelper->getName()}}
        </div>
        {{Form::model($formHelper->getModel(),['class'=>'frm_'.$formHelper->getName(),'id'=>'frm_'.$formHelper->getName()])}}
        <div class="card-body card-block">

            @foreach($formHelper->getFormItems() as $fieldName => $field)
                {!! $field->render() !!}
            @endforeach



        </div>
        <div class="card-footer">
            {!! Form::submit('Mentés',['class'=>'btn btn-primary']); !!}
        </div>
        {{Form::close()}}
    </div>
@endsection