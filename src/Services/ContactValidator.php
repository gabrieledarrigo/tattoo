<?php

namespace Services;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Validate an array of parameters.
 */
class ContactValidator extends AbstractValidator {

    protected $validator;
    protected $rules = array();
    
    /**
     * Set the rules that submitted email must respect.
     * 
     * @param type $app
     */
    public function __construct($app) {
        $this->validator = $app['validator'];
        
        $this->rules = array(
            'name' => new Assert\NotBlank(),
            'email' => new Assert\Email(),
            'message' => new Assert\NotBlank()
        );
    }

}