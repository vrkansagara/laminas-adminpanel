<?php

namespace Blog\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Blog\Form\PostForm;
use Blog\Entity\Post;
use Blog\Form\CommentForm;

/**
 * This is the Post controller class of the Blog application. 
 * This controller is used for managing posts (adding/editing/viewing/deleting).
 */
class PostController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager 
     */
    private $entityManager;

    /**
     * Post manager.
     * @var Blog\Service\PostManager
     */
    private $postManager;

    /**
     * Constructor is used for injecting dependencies into the controller.
     */
    public function __construct($entityManager, $postManager)
    {
        $this->entityManager = $entityManager;
        $this->postManager = $postManager;
    }

    /**
     * This action displays the "New Post" page. The page contains a form allowing
     * to enter post title, content and tags. When the user clicks the Submit button,
     * a new Post entity will be created.
     */
    public function addAction()
    {
        // Create the form.
        $form = new PostForm();

        // Check whether this post is a POST request.
        if ($this->getRequest()->isPost()) {

            // Get POST data.
            $data = $this->params()->fromPost();

            // Fill form with data.
            $form->setData($data);
            if ($form->isValid()) {

                // Get validated form data.
                $data = $form->getData();

                // Use post manager service to add new post to database.
                $this->postManager->addNewPost($data);

                // Redirect the user to "index" page.
                return $this->redirect()->toRoute('blog');
            }
        }

        // Render the view template.
        return new ViewModel([
            'form' => $form
        ]);
    }

    /**
     * This action displays the "View Post" page allowing to see the post title
     * and content. The page also contains a form allowing
     * to add a comment to post. 
     */
    public function viewAction()
    {
        $postId = (int) $this->params()->fromRoute('id', -1);

        // Validate input parameter
        if ($postId < 0) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Find the post by ID
        $post = $this->entityManager->getRepository(Post::class)
            ->findOneById($postId);

        if ($post == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Create the form.
        $form = new CommentForm();

        // Check whether this post is a POST request.
        if ($this->getRequest()->isPost()) {

            // Get POST data.
            $data = $this->params()->fromPost();

            // Fill form with data.
            $form->setData($data);
            if ($form->isValid()) {

                // Get validated form data.
                $data = $form->getData();

                // Use post manager service to add new comment to post.
                $this->postManager->addCommentToPost($post, $data);

                // Redirect the user again to "view" page.
                return $this->redirect()->toRoute('blog/posts', ['action' => 'view', 'id' => $postId]);
            }
        }

        // Render the view template.
        return new ViewModel([
            'post' => $post,
            'form' => $form,
            'postManager' => $this->postManager
        ]);
    }

    /**
     * This action displays the page allowing to edit a post.
     */
    public function editAction()
    {
        // Create form.
        $form = new PostForm();

        // Get post ID.
        $postId = (int) $this->params()->fromRoute('id', -1);

        // Validate input parameter
        if ($postId < 0) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Find the existing post in the database.
        $post = $this->entityManager->getRepository(Post::class)
            ->findOneById($postId);
        if ($post == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Check whether this post is a POST request.
        if ($this->getRequest()->isPost()) {

            // Get POST data.
            $data = $this->params()->fromPost();

            // Fill form with data.
            $form->setData($data);
            if ($form->isValid()) {

                // Get validated form data.
                $data = $form->getData();

                // Use post manager service update existing post.                
                $this->postManager->updatePost($post, $data);

                // Redirect the user to "admin" page.
                return $this->redirect()->toRoute('blog', ['action' => 'admin']);
            }
        } else {
            $data = [
                'title' => $post->getTitle(),
                'content' => $post->getContent(),
                'tags' => $this->postManager->convertTagsToString($post),
                'status' => $post->getStatus()
            ];

            $form->setData($data);
        }

        // Render the view template.
        return new ViewModel([
            'form' => $form,
            'post' => $post
        ]);
    }

    /**
     * This "delete" action deletes the given post.
     */
    public function deleteAction()
    {
        //
        return $this->redirect()->toRoute('blog', ['action' => 'admin']);

        $postId = (int) $this->params()->fromRoute('id', -1);

        // Validate input parameter
        if ($postId < 0) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $post = $this->entityManager->getRepository(Post::class)
            ->findOneById($postId);
        if ($post == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $this->postManager->removePost($post);

        // Redirect the user to "admin" page.
        return $this->redirect()->toRoute('blog/posts', ['action' => 'admin']);
    }

    /**
     * This "admin" action displays the Manage Posts page. This page contains
     * the list of posts with an ability to edit/delete any post.
     */
    public function adminAction()
    {
        // Get recent posts
        $posts = $this->entityManager->getRepository(Post::class)
            ->findBy([], ['dateCreated' => 'DESC']);

        // Render the view template
        return new ViewModel([
            'posts' => $posts,
            'postManager' => $this->postManager
        ]);
    }
}
