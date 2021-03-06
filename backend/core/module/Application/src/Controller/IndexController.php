<?php

declare(strict_types=1);

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Text\Figlet\Figlet;
use Laminas\View\Model\ViewModel;

class IndexController extends AbstractActionController
{


    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Constructor. Its purpose is to inject dependencies into the Controller.
     */
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function indexAction()
    {
        $layoutData = [
            'figlet'=> new Figlet()
        ];
        return new ViewModel($layoutData);
    }
    /**
     * The "settings" action displays the info about currently logged in user.
     */
    public function settingsAction()
    {
        // Use the CurrentUser Controller plugin to get the current user.
        $user = $this->currentUser();

        if ($user == null) {
            throw new \Exception('Not logged in');
        }

        return new ViewModel([
            'user' => $user
        ]);
    }
}
