<?php /** @var \App\Http\Components\FormHelper\FormFieldHelper $field */ ?>

<div class="form-group">
    {{ Form::label($field->getLabel(), null, ['for' => $field->getName()]) }}
    <div class="input-group">
        @if($field->hasIcon())
            <div class="input-group-addon">
                <i class="{{$field->getIconClass()}}"></i>
            </div>
        @endif
        {{ Form::input($field->getType(),$field->getName(),$field->getValue(),
        array_merge(
        ['id'=>$field->getName(), 'class' => 'form-control pull-right'],
         $field->isDisabled() ? ['disabled' => 'disabled'] : []
         )
           )}}
    </div>
</div>
