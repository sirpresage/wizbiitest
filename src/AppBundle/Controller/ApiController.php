<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Document\Measure;

class ApiController extends Controller
{
    /**
     * @Route("/collect")
     */
    public function collectAction(Request $request)
    {
        // Retrieve the mobile detector service
        $mobileDetector = $this->get('mobile_detect.mobile_detector');

        if($request->getMethod() === "GET") {
            $measure = new Measure($request->query->all());
            
            //Check device type, note that a tablet is treated as a mobile device
            $measure->setIsMobile($mobileDetector->isMobile());

            $validator = $this->get('validator');
            $dm = $this->get('doctrine_mongodb')->getManager();
            $errors = $validator->validate($measure);
            if(count($errors) == 0) {
                $dm->persist($measure);
                $dm->flush();
                return new Response("OK");
            } else{
                return new Response($errors);
            }
        } else {
            return $this->render('@App/Api/collect.html.twig', array(
                // ...
            ));
        } 


    }

}
