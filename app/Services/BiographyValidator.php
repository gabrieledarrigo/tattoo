<?php

namespace Services;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Validate an array of parameters.
 * @author gabriele d'arrigo - @acirdesign
 */
class BiographyValidator {

    protected $validator;
    protected $rules = array();
    
    /**
     * Se the rules that submitted value must respect.
     * @param type $app
     */
    public function __construct($app) {
        $this->validator = $app['validator'];

        $this->rules = array(
            'title' => new Assert\NotBlank(),
            'description' => new Assert\NotBlank(),
            'published' => new Assert\EqualTo(array('value' => 'SI')),
            'date' => new Assert\DateTime()
        );
    }
    
    /**
     * Validate method used by clients.
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