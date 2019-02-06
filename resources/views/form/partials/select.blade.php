<?php /** @var \App\Http\Components\FormHelper\FormSelectFieldHelper $field */ ?>
<div class="form-group">
    {{ Form::label($field->getName(), $field->getLabel()) }}
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
            @foreach ($field->getErrors() as $error)
                @if ($loop->first)
                    <div class="invalid-feedback">
                        <ul class="ml-3">
                @endif
                            <li>{{$error}}</li>
                @if ($loop->last)
                        </ul>
                    </div>
                @endif

            @endforeach

    </div>
    @if($field->hasDescription())
        <small class="help-block form-text">{{$field->getDescription()}}</small>
    @endif
</div>
