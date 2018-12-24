<?php /** @var \App\Http\Components\FormHelper\FormHelper $formHelper */ ?>
@extends('layouts.app')
@section('page_title')
{{$formHelper->getTitle()}}
@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-body">
            {{Form::model($formHelper->getModel(),['class'=>'frm_'.$formHelper->getName(),'id'=>'frm_'.$formHelper->getName()])}}

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
            <div class="row">
                <div class="col-xs-12 col-lg-10 col-lg-offset-1">

                    @foreach($formHelper->getFormItems() as $fieldName => $field)
                        {!! $field->render() !!}
                    @endforeach

                </div><!-- /.col- -->
            </div><!-- /.row -->
            <div class="row">
                <div class="col-xs-12 buttons">
                    <div class="right">
                        @if(isset($P['values'])) {{-- new --}}
                        <button type="button" id="btn-new" class="btn btn-new bg-primary "><i class="fa fa-plus"></i> <span>New</span> </button>
                        @endif
                        <button type="submit" id="btn-save" class="btn btn-save bg-primary "><i class="fa fa-save"></i> <span>Save</span> </button>
                        @if(isset($P['values'])) {{-- new --}}
                        <button type="button" id="btn-copy" class="btn btn-copy bg-primary "><i class="fa fa-copy"></i> <span>Copy</span> </button>
                        <button type="button" id="btn-delete" class="btn btn-delete bg-primary "><i class="fa fa-trash"></i> <span>Delete</span> </button>
                        @endif
                        <button type="button" id="btn-cancel" class="btn btn-cancel bg-primary "><i class="fa fa-close"></i> <span>Cancel</span> </button>
                    </div>
                </div>
            </div>
            {{Form::close()}}
        </div> <!-- /.box-body -->
    </div>
@stop