<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{
    /**
     * @Route("/collect")
     */
    public function collectAction()
    {
        return $this->render('@App/Api/collect.html.twig', array(
            // ...
        ));
    }

}
