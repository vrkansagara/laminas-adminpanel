<?php

declare(strict_types=1);

namespace BlogSqlite\Form;

use BlogSqlite\Model\Post;
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
                'required' => 'required',
            ],
        ]);

        $this->add([
            'type' => 'textarea',
            'name' => 'text',
            'options' => [
                'label' => 'Post Text',
            ],
            'attributes' => [
                'required' => 'required',
            ],
        ]);
    }
}
