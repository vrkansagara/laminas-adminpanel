<?php declare(strict_types=1);

namespace Blog\Controller;

use Blog\Form\PostForm;
use Blog\Model\PostCommandInterface;
use Blog\Model\PostRepositoryInterface;
use InvalidArgumentException;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class WriteController extends AbstractActionController
{
    /**
     * @var PostCommandInterface
     */
    private $command;

    /**
     * @var PostForm
     */
    private $form;

    /**
     * @var PostRepositoryInterface
     */
    private $repository;

    /**
     * @param PostCommandInterface $command
     * @param PostForm $form
     * @param PostRepositoryInterface $repository
     */
    public function __construct(
        PostCommandInterface $command,
        PostForm $form,
        PostRepositoryInterface $repository
    )
    {
        $this->command = $command;
        $this->form = $form;
        $this->repository = $repository;
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
            $messages = $this->form->getMessages();
//            echo '<pre>'; print_r($messages); echo __FILE__; echo __LINE__; exit;
            return $viewModel;
        }

        $post = $this->form->getData();

        try {
            $post = $this->command->insertPost($post);
        } catch (\Exception $ex) {
            // An exception occurred; we may want to log this later and/or
            // report it to the user. For now, we'll just re-throw.
            throw $ex;
        }


        return $this->redirect()->toRoute('blog',);
//        return $this->redirect()->toRoute(
//            'blog/detail',
//            ['id' => $post->getId()]
//        );
    }

    public function editAction()
    {
        $id = $this->params()->fromRoute('id');
        if (!$id) {
            return $this->redirect()->toRoute('blog');
        }

        try {
            $post = $this->repository->findPost($id);
        } catch (\InvalidArgumentException $ex) {
            return $this->redirect()->toRoute('blog');
        }

        $this->form->bind($post);
        $viewModel = new ViewModel(['form' => $this->form]);

        $request = $this->getRequest();
        if (!$request->isPost()) {
            return $viewModel;
        }

        $this->form->setData($request->getPost());

        if (!$this->form->isValid()) {
            return $viewModel;
        }

        $post = $this->command->updatePost($post);
        return $this->redirect()->toRoute(
            'blog/detail',
            ['id' => $post->getId()]
        );
    }

    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id');
        if (!$id) {
            return $this->redirect()->toRoute('blog');
        }

        try {
            $post = $this->repository->findPost($id);
        } catch (InvalidArgumentException $ex) {
            return $this->redirect()->toRoute('blog');
        }

        $request = $this->getRequest();
        if (!$request->isPost()) {
            return new ViewModel(['post' => $post]);
        }

        if ($id != $request->getPost('id')
            || 'Delete' !== $request->getPost('confirm', 'no')
        ) {
            return $this->redirect()->toRoute('blog');
        }

        $post = $this->command->deletePost($post);
        return $this->redirect()->toRoute('blog');
    }
}