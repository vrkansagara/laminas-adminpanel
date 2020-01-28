<?php


namespace Application\Controller;

use Carbon\Carbon;
use Laminas\Form\Element\DateTime;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;

class PingController extends AbstractActionController
{
    public function indexAction()
    {
        $responseData = [
            'time' => Carbon::now()
        ];
        return new JsonModel($responseData);
    }
}
