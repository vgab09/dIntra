<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.12.08.
 * Time: 12:10
 */

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
     */
    protected function isUnique(){
        return empty($this->getKey()) ? Rule::unique($this->getTable()) : Rule::unique($this->getTable())->ignore($this->getKey());
    }

    /**
     * Validate
     * @param $data
     * @param string $ruleName
     * @return bool
     */
    public function validate($data,$ruleName = null){

        return $this->baseValidate($data,$this->getValidationRules(),$ruleName);
    }

    /**
     * Validate given rules
     * @param $data
     * @param  array $rules
     * @return bool
     */
    public function validateByThis($data,$rules = []){
        return $this->baseValidate($data,$rules,null);
    }

    /**
     * @param $data
     * @param array $validationRules
     * @param string $ruleName
     * @return bool
     */
    protected function baseValidate($data,$validationRules, $ruleName){

        if(empty($ruleName)){
            $rules = $validationRules;
        }
        elseif(array_key_exists($ruleName,$validationRules)){
            $rules = $validationRules[$ruleName];
        }
        else{
            return false;
        }

        $validator = Validator::make($data,$rules);

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