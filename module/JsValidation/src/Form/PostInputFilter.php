<?php

declare(strict_types=1);

namespace JsValidation\Form;

use Laminas\Filter\StringTrim;
use Laminas\Filter\StripNewlines;
use Laminas\Filter\StripTags;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\StringLength;

class PostInputFilter extends InputFilter
{
    public function init()
    {
        $inputFilter = new InputFilter();
        $inputFilter->add([
            'name' => 'title',
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
            'name' => 'text',
            'required' => true,
            'filters' => [
                ['name' => StringTrim::class],
            ],
            'validators' => [
                ['name' => StringLength::class, 'options' => ['min' => 3, 'max' => 255]]
            ],
        ]);

        return $inputFilter;
    }
}
