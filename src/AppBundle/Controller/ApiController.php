<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
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
        } elseif($request->getMethod() === "POST") {
            $encoders = array(new JsonEncoder());
            $normalizers = array(new ArrayDenormalizer(), new ObjectNormalizer());
            $serializer = new Serializer($normalizers, $encoders);
 
            $measures = $serializer->deserialize($request->getContent(), 'AppBundle\Document\Measure[]', 'json');
	    
            $valid=true;
	    foreach($measures as $measure) {
		$validator = $this->get('validator');
		$dm = $this->get('doctrine_mongodb')->getManager();
		$err = $validator->validate($measure);
		
		if(count($err) == 0) {
		    $dm->persist($measure);
		    $dm->flush();
		    
		} else{
                    $valid=false;
		    $errors[] = $err;
		}
           }

            if($valid){
                return new Response("OK POST");
            }else{
                $err = $this->container->get('jms_serializer')->serialize($errors, 'json');
                return new Response($err);
            }
        } else {
            return $this->render('@App/Api/collect.html.twig', array(
                // ...
            ));
        } 


    }

}
