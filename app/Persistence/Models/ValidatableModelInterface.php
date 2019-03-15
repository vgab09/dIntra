<?php

namespace App\Persistence\Models;

use Illuminate\Support\MessageBag;

interface ValidatableModelInterface
{
    /**
     * Validate
     * @param string $ruleName
     * @return bool
     */
    public function validate($ruleName = null);

    /**
     * @param string|null $ruleName
     * @return bool
     */
    public function isValid($ruleName = null);

    /**
     * Validate given rules
     * @param  array $rules
     * @return bool
     */
    public function validateByThis($rules = []);

    /**
     * Get model's validation rules
     * @return array
     */
    public function getValidationRules():array;


    /**
     * Get validation errors MessageBag object
     * @return MessageBag
     */
    public function getValidationErrorMessageBag() : MessageBag;

    /**
     * Get validation error messages
     * @param null $format
     * @return array
     */
    public function getValidationErrorMessages($format = null) : array;

    /**
     * Get Validator instance. after the validation
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function getValidatorInstance();

    /**
     * Convert the model's attributes to an array.
     *
     * @return array
     */
    public function attributesToArray();


}