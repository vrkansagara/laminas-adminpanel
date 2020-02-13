<?php

declare(strict_types=1);

namespace Blog\Form;

use Laminas\Filter\StringTrim;
use Laminas\Filter\StripNewlines;
use Laminas\Filter\StripTags;
use Laminas\Form\Form;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\StringLength;

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
            'type' => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Insert new Post',
            ],
        ]);

//        $this->setValidationGroup([
//            'csrf',
//        ]);
    }




    /**
     * This method creates input filter (used for form filtering/validation).
     */
    private function addInputFilter()
    {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        $inputFilter->add([
            'name' => 'post[title]',
            'required' => true,
            'filters' => [
                ['name' => StringTrim::class],
                ['name' => StripTags::class],
                ['name' => StripNewlines::class],
            ],
            'validators' => [
                ['name' => StringLength::class, 'options' => ['min' => 3, 'max' => 128],
                ],
            ],
        ]);

        // Add input for "street_address" field
        $inputFilter->add([
            'name' => 'post[text]',
            'required' => true,
            'filters' => [
                ['name' => StringTrim::class],
            ],
            'validators' => [
                ['name' => StringLength::class, 'options' => ['min' => 3, 'max' => 255]]
            ],
        ]);
    }
}
