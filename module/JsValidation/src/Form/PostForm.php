<?php

namespace JsValidation\Form;

use Laminas\Form\Form;
use Laminas\InputFilter\InputFilter;

class PostForm extends Form
{
    public function init()
    {
        $this->addInputFilter();
        $this->add([
            'name' => 'post',
            'type' => PostFieldset::class,
            'options' => [
                'use_as_base_fieldset' => true,
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'isAdmin',
            'attributes' => [
                'value' => 'Is admin',
            ],
        ]);

        $this->add([
            'type' => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Insert new Post',
            ],
        ]);
    }


    /**
     * This method creates input filter (used for form filtering/validation).
     */
    private function addInputFilter()
    {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        $inputFilter->add([
            'name' => 'title',
            'required' => true,
            'validators' => [
                [
                    'name' => 'NotEmpty',
                ],
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 5
                    ],
                ],
            ],
        ]);

        // Add input for "street_address" field
        $inputFilter->add([
            'name' => 'text',
            'required' => true,
            'validators' => [
                [
                    'name' => 'NotEmpty',
                ],
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 15
                    ],
                ],
            ]

        ]);
    }
}
