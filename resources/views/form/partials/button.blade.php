<?php /** @var \App\Http\Components\FormHelper\FormCheckboxFieldHelper $field */ ?>

<button
        id="{{$field->getElementId()}}"
        class="{{$field->getClass()}}"
        name="{{$field->getFieldName()}}"
        type="{{$field->getType()}}"
        {{!$field->isDisabled() ?: 'disabled'}}
                >
    @if($field->hasIcon())
        <i class="{{$field->getIconClass()}}"></i>
    @endif
    {{$field->getValue()}}
</button>