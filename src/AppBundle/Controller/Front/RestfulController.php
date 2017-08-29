<?php
namespace AppBundle\Controller\Front;

use AppBundle\Controller\FrontController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;

class RestfulController extends FOSRestController
{
    /**
     * @Rest\Get("/rest-api")
     */
    public function getAction()
    {
        dump('Test GET');
        die;
    }
}