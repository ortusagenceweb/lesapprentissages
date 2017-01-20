<?php

namespace LAP\EquipeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class DefaultController extends Controller
{
	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
    public function indexAction()
    {
        /* All static pages */
		$repository = $this->getDoctrine()->getManager()->getRepository('LAPUtilisateurBundle:User');
		$listeAllmembers = $repository->findAllonsite();
		
		return $this->render('LAPEquipeBundle:Default:index.html.twig', array('listeAllmembers' => $listeAllmembers));
    }
}
