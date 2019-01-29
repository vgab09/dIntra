<?php /** @var \App\Http\Components\FormHelper\FormCheckboxFieldHelper $field */ ?>

<div class="form-group">
    <div class="{{$field->getWrapperClass()}}">
        {{$field->renderTag()}}
        {{ Form::label($field->getName(),$field->getLabel(), ['class'=>'custom-control-label']) }}
    </div>
</div>
