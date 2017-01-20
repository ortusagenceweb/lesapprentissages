<?php

namespace LAP\AccueilBundle\Controller;

use LAP\StaticpagesBundle\Entity\Pages;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class DefaultController extends Controller
{
	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
    public function indexAction()
    {
		/* Static pages */
		$repository = $this->getDoctrine()->getManager()->getRepository('LAPStaticpagesBundle:Pages');
		$pages = $repository->findAll();
		
        return $this->render('LAPAccueilBundle:Accueil:index.html.twig', array(
			'pages' => $pages
		));
    }
}
