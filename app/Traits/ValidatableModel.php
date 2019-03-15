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
     * @var \Illuminate\Contracts\Validation\Validator
     */
    private $validator;

    /**
     * return all validation rules
     * @return array
     */
    public function getValidationRules():array {
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
     * @param string|null $ruleName
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validate($ruleName = null){

        $this->makeValidator($this->getValidationRules(),$ruleName);
        $this->validator->validate();
    }

    /**
     * @param string|null $ruleName
     * @return bool
     */
    public function isValid($ruleName = null){
        $this->makeValidator($this->getValidationRules(),$ruleName);

        if($this->validator->fails()){
            $this->validationErrors = $this->validator->errors();
            return false;
        }
        return true;
    }

    /**
     * Validate given rules
     * @param  array $rules
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validateByThis($rules = []){
        $this->makeValidator($rules,null);
        $this->validator->validate();
    }

    /**
     * @param $validationRules
     * @param $ruleName
     * @return \Illuminate\Contracts\Validation\Validator|\Illuminate\Validation\Factory
     */

    protected function makeValidator($validationRules, $ruleName = null){

        if(empty($ruleName)){
            $rules = $validationRules;
        }
        elseif(array_key_exists($ruleName,$validationRules)){
            $rules = $validationRules[$ruleName];
        }
        else{
            $rules = [];
        }

        $this->validator = Validator::make($this->attributesToArray(),$rules);
        return $this->validator;
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

    /**
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function getValidatorInstance(){
        if($this->validator === null){
            $this->makeValidator($this->getValidationRules());
        }
        return clone $this->validator;
    }


}