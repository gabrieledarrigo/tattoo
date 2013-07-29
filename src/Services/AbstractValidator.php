<?php

namespace Services;

use Symfony\Component\Validator\Constraints as Assert;

abstract class AbstractValidator {

    protected $validator;
    protected $rules = array();

    /**
     * Validate method used by client classes.
     * 
     * @param type $params
     * @return type
     */
    public function validate($params) {

        $assertProperty = array();

        // Dinamically retrieve the constraint base on submitted params.
        foreach ($params as $key => $value) {

            if (array_key_exists($key, $this->rules)) {
                $assertProperty[$key] = $this->rules[$key];
            }
        }

        $constraint = new Assert\Collection($assertProperty);

        $errors = $this->validator->validateValue($params, $constraint);
        
        // Return results.
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $out[] = $error->getPropertyPath() . ' ' . $error->getMessage() . "\n";
            }
            return array('error' => $out);
        } else {
            return $params;
        }
    }

}