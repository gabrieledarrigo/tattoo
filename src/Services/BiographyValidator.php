<?php

namespace Services;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Validate an array of parameters.
 * 
 * @author gabriele d'arrigo - @acirdesign
 */
class BiographyValidator extends AbstractValidator {

    protected $validator;
    protected $rules = array();

    /**
     * Set the rules that submitted's value must respect.
     * 
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

}