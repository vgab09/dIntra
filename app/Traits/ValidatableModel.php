<?php

namespace App\Traits;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Rule;

trait ValidatableModel
{

    /**
     * @var MessageBag
     */
    private $validationErrors;

    /**
     * return all validation rules
     * @return array
     */
    protected function getValidationRules():array {
        return isset($this->rules) ? $this->rules : [];
    }

    /**
     * If model exist except current attribute
     * @param string $fieldName
     * @return \Illuminate\Validation\Rules\Unique
     */
    protected function isUnique(string $fieldName){
        return empty($this->getKey()) ? Rule::unique($this->getTable(),$fieldName) : Rule::unique($this->getTable(),$fieldName)->ignore($this->getKey(),$this->getKeyName());
    }

    /**
     * Validate
     * @param string $ruleName
     * @return bool
     */
    public function validate($ruleName = null){

        return $this->baseValidate($this->getValidationRules(),$ruleName);
    }

    /**
     * Validate given rules
     * @param  array $rules
     * @return bool
     */
    public function validateByThis($rules = []){
        return $this->baseValidate($rules,null);
    }

    /**
     * @param $data
     * @param array $validationRules
     * @param string $ruleName
     * @return bool
     */
    protected function baseValidate($validationRules, $ruleName){

        if(empty($ruleName)){
            $rules = $validationRules;
        }
        elseif(array_key_exists($ruleName,$validationRules)){
            $rules = $validationRules[$ruleName];
        }
        else{
            return false;
        }

        $validator = Validator::make($this->attributesToArray(),$rules);

        if($validator->fails()){
            $this->validationErrors = $validator->errors();
            return false;
        }

        return true;
    }

    /**
     * Get validation errors MessageBag object
     * @return MessageBag
     */
    public function getValidationErrorMessageBag() : MessageBag{
        return get_class($this->validationErrors) == MessageBag::class ? $this->validationErrors : new MessageBag();
    }

    /**
     * Get validation error messages
     * @param null $format
     * @return array
     */
    public function getValidationErrorMessages($format = null) : array{
        return $this->getValidationErrorMessageBag()->all($format);
    }


}