<?php declare(strict_types=1);

namespace JsValidation\Controller;

use JsValidation\Form\PostForm;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class IndexController extends AbstractActionController
{

    /**
     * @var PostForm
     */
    private $form;

    /**
     * IndexController constructor.
     * @param PostForm $form
     */
    public function __construct(
        PostForm $form
    )
    {
        $this->form = $form;
    }

    public function indexAction()
    {
        $original = 'my_original_content';

// Attach a filter
        $filter   = new \Laminas\Filter\Word\UnderscoreToCamelCase();
        $filtered = $filter->filter($original);
//        echo $filtered;
    }

    public function addAction()
    {
        $request = $this->getRequest();
        $viewModel = new ViewModel(['form' => $this->form]);

        if (!$request->isPost()) {
            return $viewModel;
        }

        $this->form->setData($request->getPost());
        if (!$this->form->isValid()) {
            echo "The form is not valid\n";
            foreach ($this->form->getInputFilter()->getInvalidInput() as $error) {
                print_r($error->getMessages());
            }
            $messages = $this->form->getMessages();
            echo '<pre>'; print_r($messages); echo __FILE__; echo __LINE__; exit;
            return $viewModel;
        }

        $post = $this->form->getData();

        try {
            echo 'Form is valid and insert logic goes her. <pre>';
            print_r($post);
            exit;
        } catch (\Exception $ex) {
            throw $ex;
        }


        return $this->redirect()->toRoute('validation',);

    }
}