<?php

declare(strict_types=1);

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
        // Create Contact Us form
        $form = new ContactForm();

        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {
            $request = $this->getRequest();
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                // $request->getFiles()->toArray()
            );


            // Fill in the form with POST data
            //            $data = $this->params()->fromPost();
            $form->setData($data);

            // Validate form
            if ($form->isValid()) {
                // Get filtered and validated data
                $validatedData = $form->getData();
                $this->mailSender->sendMail('vrkansagara@gmail.com', $validatedData['email'], $validatedData['subject'], $validatedData['body']);

                // Redirect to "Thank You" page
                return $this->redirect()->toRoute('contact', ['action' => 'thankYou']);
            } else {
                $messages = $form->getMessages();
                echo '<pre>';
                var_dump($messages);
                exit;
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
