<?php

namespace JsValidation\Form;

use Blog\Model\Post;
use Laminas\Form\Fieldset;
use Laminas\Hydrator\Reflection as ReflectionHydrator;
use Laminas\Validator\StringLength;

class PostFieldset extends Fieldset
{
    public function init()
    {
        $this->setHydrator(new ReflectionHydrator());
        $this->setObject(new Post('', ''));


        $this->add([
            'type' => 'hidden',
            'name' => 'id',
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'title',
            'options' => [
                'label' => 'Post Title',
            ],
            'attributes' => [
//                'required' => 'required',
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'min' => 3,
                        'max' => 256
                    ],
                ],
            ],
        ]);

        $this->add([
            'type' => 'textarea',
            'name' => 'text',
            'options' => [
                'label' => 'Post Text',
            ],
        ]);

//        $this->add([
//            'type' => Csrf::class,
//            'name' => 'csrf',
//            'options' => [
//                'csrf_options' => [
//                    'timeout' => 600,
//                ],
//            ],
//        ]);
    }
}
