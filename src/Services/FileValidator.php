<?php

namespace Services;

use Symfony\Component\Validator\Constraints as Assert;

class FileValidator extends AbstractValidator {

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
            'image' => new Assert\NotBlank(),
            'description' => new Assert\Blank(),
            'published' => new Assert\EqualTo(array('value' => 'SI')),
            'date' => new Assert\DateTime()
        );
    }

}