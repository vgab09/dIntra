<?php /** @var \App\Http\Components\FormHelper\FormHelper $formHelper */ ?>
@extends('layouts.app')
@section('page_title')
{{$formHelper->getTitle()}}
@endsection
@section('content')
    <div class="card">
        {{Form::model($formHelper->getModel(),['class'=>'frm_'.$formHelper->getName(),'id'=>'frm_'.$formHelper->getName()])}}
        <div class="card-body card-block">


{{--


            @if( ! empty($P['msg']))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-ban"></i> Sikertelen mentés!</h4>
                    <ul>
                        @foreach($P['msg'] as $msg)
                            <li>{{$msg}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
--}}

            @foreach($formHelper->getFormItems() as $fieldName => $field)
                {!! $field->render() !!}
            @endforeach



        </div>
        <div class="card-footer">
            {!! Form::submit('Mentés'); !!}
        </div>
        {{Form::close()}}
    </div>
@endsection