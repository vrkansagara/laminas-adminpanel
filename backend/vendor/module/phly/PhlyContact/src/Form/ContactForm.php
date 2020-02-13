<?php

declare(strict_types=1);

namespace PhlyContact\Form;

use Laminas\Captcha\AdapterInterface as CaptchaAdapter;
use Laminas\Form\Element;
use Laminas\Form\Form;

class ContactForm extends Form
{
    protected $captchaAdapter;
    protected $csrfToken;

    public function __construct($name = null, CaptchaAdapter $captchaAdapter = null)
    {
        parent::__construct($name);

        if (null !== $captchaAdapter) {
            $this->captchaAdapter = $captchaAdapter;
        }

        $this->init();
    }

    public function init()
    {
        $name = $this->getName();
        if (null === $name) {
            $this->setName('contact');
        }

        $this->add([
            'name' => 'from',
            'type' => 'text',
            'options' => [
                'label' => 'Email address :',
            ],
            'attributes' => [
                'class' => 'form-control',
                'type' => 'email',
            ]
        ]);

        $this->add([
            'name' => 'subject',
            'type' => 'Text',
            'options' => [
                'label' => 'Subject:',
            ],
            'attributes' => [
                'class' => 'form-control',
            ]
        ]);


        $this->add([
            'name' => 'body',
            'type' => 'Textarea',
            'options' => [
                'label' => 'Your message:',
            ],
            'attributes' => [
                'class' => 'form-control'
            ]
        ]);

        $captcha = new Element\Captcha('captcha');
        $captcha->setCaptcha($this->captchaAdapter);
        $captcha->setOptions([
            'label' => 'Please verify you are human.'
        ]);
        $captcha->setAttributes([
            'class' => 'form-control',
        ]);
        $this->add($captcha);

        $this->add(new Element\Csrf('csrf'));

        $this->add([
            'name' => 'Send',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Send',
                'class' => 'btn btn-primary',
            ],
        ]);
    }
}
