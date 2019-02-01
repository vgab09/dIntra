<?php /** @var \App\Http\Components\FormHelper\FormCheckboxFieldHelper $field */ ?>

<div class="form-group">
    {{ Form::label($field->getName(),$field->getLabel()) }}
    <div class="{{$field->getWrapperClass()}}">
        {{$field->renderTag()}}
        {{ Form::label($field->getName(),' ', ['class'=>'custom-control-label']) }}
    </div>
</div>
