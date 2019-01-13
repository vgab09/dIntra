<?php /** @var \App\Http\Components\FormHelper\FormSelectFieldHelper $field */ ?>

<div class="form-group">
    {{ Form::label($field->getLabel(), null, ['for' => $field->getName()]) }}
    <div class="input-group">
        @if($field->hasIcon())
            <div class="input-group-addon">
                <i class="{{$field->getIconClass()}}"></i>
            </div>
        @endif
        {{ $field->renderTag() }}
        @if($field->hasSuffix())
            <div class="input-group-addon">
                {{ $field->getSuffix() }}
            </div>
        @endif

    </div>
    @if($field->hasDescription())
        <small class="help-block form-text">{{$field->getDescription()}}</small>
    @endif
</div>
