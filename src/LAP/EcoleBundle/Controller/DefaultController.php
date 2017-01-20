<?php

namespace LAP\EcoleBundle\Controller;

use LAP\StaticpagesBundle\Entity\Pages;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class DefaultController extends Controller
{
	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
    public function indexAction(Request $request)
    {
		/* All static pages */
		$repository = $this->getDoctrine()->getManager()->getRepository('LAPStaticpagesBundle:Pages');
		$listeAllpages = $repository->findAll();
		
        return $this->render('LAPEcoleBundle:Default:index.html.twig', array(
			'listeAllpages' => $listeAllpages
		));
    }
}
