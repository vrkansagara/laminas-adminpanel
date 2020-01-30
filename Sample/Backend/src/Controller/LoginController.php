<?php

namespace Backend\Controller;

use Backend\Interfaces\LoginInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class LoginController extends AbstractActionController implements LoginInterface
{
    private $footerScript;

    public function __construct($footerScript)
    {
        $this->footerScript = $footerScript;
    }


    public function indexAction()
    {
        die('There is nothing with this controller!');
    }

    public function showLoginAction()
    {

        // Generate script here.
        $javaScript = <<< JS
 jQuery(document).ready(function(){
        $("#loginForm").each(function() {
            $(this).validate({
                errorElement: 'span',
                errorClass: 'invalid-feedback',
                errorPlacement: function (error, element) {
                    if (element.parent('.input-group').length ||
                        element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
                        error.insertAfter(element.parent());
                        // else just place the validation message immediately after the input
                    } else {
                        error.insertAfter(element);
                    }
                },
                highlight: function (element) {
                    $(element).closest('.form-control').removeClass('is-valid').addClass('is-invalid'); // add the Bootstrap error class to the control group
                },

                

                unhighlight: function(element) {
                    $(element).closest('.form-control').removeClass('is-invalid').addClass('is-valid');
                },

                success: function (element) {
                    $(element).closest('.form-control').removeClass('is-invalid').addClass('is-valid'); // remove the Boostrap error class from the control group
                },

                focusInvalid: false, // do not focus the last invalid input
                
                rules: {
                    "email":{
                        "laravelValidation":[
                                    ["Required",[],"Email is requireddddd",true],
                                    ["String",[],"The email must be a string.",false]
                                ]
                            },
                    "password":{
                        "laravelValidation":[
                                    ["Required",[],"Email is requireddddd",true],
                                    ["String",[],"The password must be a string.",false]
                                ]
                    }
                }
            });
        });
    });
JS;

        $this->footerScript->appendFile('/assets/js/jsvalidation.js');
        $this->footerScript->appendScript($javaScript);

        return new ViewModel();
    }

    public function processLoginAction()
    {
        // TODO: Implement processLoginAction() method.
    }

    public function loginRedirectAction()
    {
        // TODO: Implement loginRedirectAction() method.
    }

    public function logoutRedirectAction()
    {
        // TODO: Implement logoutRedirectAction() method.
    }
}
