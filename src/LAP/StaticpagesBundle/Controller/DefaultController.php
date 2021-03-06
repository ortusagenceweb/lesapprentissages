<?php

namespace LAP\StaticpagesBundle\Controller;

use LAP\StaticpagesBundle\Entity\Pages;
use LAP\StaticpagesBundle\Form\PageEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DefaultController extends Controller
{
    /**
	* @Security("has_role('ROLE_ADMIN')")
	*/
	public function indexAction(Request $request)
    {
		/* Call the Injection service */
		$inject = $this->container->get('lap_admin.inject');
		
		/* Connected User data */
		$u = $this->get('security.token_storage')->getToken()->getUser();
		$util = $inject->connectedUserdatas($u);
		
		/* All static pages */
		$repository = $this->getDoctrine()->getManager()->getRepository('LAPStaticpagesBundle:Pages');
		$listeAllpages = $repository->findAll();

        /* Request for the notifications in the header page */
        $listes = $inject->miniListes( $util['id'] );
		
		/* Limit number of characters in title to 45 */
		$t = $this->container->get('lap_admin.cuttxt');
        $listeLastmessagerie = $listes[0];

        $inject->limit45($listeLastmessagerie, $t);
        $inject->limitStaticpages80($listeAllpages, $t);
		
		return $this->render('LAPStaticpagesBundle:Default:index.html.twig', array(
			'name'                  => $util['name'],
			'surname'               => $util['surname'],
			'picture'               => $util['picture'],
			'listeAllpages'         => $listeAllpages,
			'listeLastmessagerie'   => $listes[0],
			'listeLast5notifs'      => $listes[1],
            'list5LastTasks'        => $listes[2]
		));
    }
	
	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
	public function viewpageAction($id, Request $request, Pages $page)
	{
		/* Call the Injection service */
		$inject = $this->container->get('lap_admin.inject');
		
		/* Connected User data */
		$u = $this->get('security.token_storage')->getToken()->getUser();
		$util = $inject->connectedUserdatas($u);
		
		$form = $this->get('form.factory')->create(PageEditType::class, $page);

        /* Request for the notifications in the header page */
        $listes = $inject->miniListes( $util['id'] );
		
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {			
			$em->flush();
			
			$request->getSession()->getFlashBag()->add('notice', 'Votre page a bien été modifié.');
			
			return $this->redirectToRoute('lap_staticpages_homepage');
		}
		
		return $this->render('LAPStaticpagesBundle:Default:viewpage.html.twig', array(
			'name'                  => $util['name'],
			'surname'               => $util['surname'],
			'picture'               => $util['picture'],
			'page'                  => $page,
            'listeLastmessagerie'   => $listes[0],
            'listeLast5notifs'      => $listes[1],
            'list5LastTasks'        => $listes[2],
			'form'                  => $form->createView()
		));
	}
}
