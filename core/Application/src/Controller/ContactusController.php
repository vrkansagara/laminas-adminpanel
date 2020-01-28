<?php

namespace Application\Controller;

use Application\Form\ContactForm;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class ContactusController extends AbstractActionController
{
    private $mailSender;

    public function __construct($mailSender)
    {
        $this->mailSender = $mailSender;
    }

    // This action displays the feedback form
    public function indexAction()
    {
        echo 1;
        exit;
        // Create Contact Us form
        $form = new ContactForm();

        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {
            $request = $this->getRequest();
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );


            // Fill in the form with POST data
            //            $data = $this->params()->fromPost();
            $form->setData($data);

            // Validate form
            if ($form->isValid()) {
                // Get filtered and validated data
                $data = $form->getData();
                $this->mailSender->sendMail('vrkansagara@gmail.com', $data['email'], $data['subject'], $data['body']);
                // ... Do something with the validated data ...

                // Redirect to "Thank You" page
                return $this->redirect()->toRoute('contact', ['action' => 'thankYou']);
            }
        }

        // Pass form variable to view
        return new ViewModel([
            'form' => $form
        ]);
    }

    // This action displays the Thank You page. The user is redirected to this
    // page on successful mail delivery.
    public function thankYouAction()
    {
        return new ViewModel();
    }

    // This action displays the Send Error page. The user is redirected to this
    // page on mail delivery error.
    public function sendErrorAction()
    {
        return new ViewModel();
    }
}
