<?php

namespace LAP\MessagerieBundle\Controller;

use LAP\MessagerieBundle\Entity\Messagerie;
use LAP\MessagerieBundle\Form\MessagerieType;
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
		$idusr = $util['id'];
		
		/* My messages */
		$repository = $this->getDoctrine()->getManager()->getRepository('LAPMessagerieBundle:Messagerie');
		$mesmessages = $repository->findMymessages($idusr);
		
		/* Formatting text for viewing in admin messagerie table */
		$t = $this->container->get('lap_admin.cuttxt');		
		foreach($mesmessages as $key => $message)
		{
			$txt = $mesmessages[$key]['texte'];
			$txtcuted = $t->cuttext($txt);
			$mesmessages[$key]['texte'] = ( trim(strip_tags(substr($txtcuted, 0, 80))) );
		}

        /* Request for the notifications in the header page */
        $listes = $inject->miniListes( $util['id'] );
		
		/* Setting "lu" value to 1 (true) directly from view message page*/
		if ($request->isMethod('POST')) {
			$em = $this->getDoctrine()->getManager();
			$message = $em->getRepository('LAPMessagerieBundle:Messagerie')->find( $request->query->get('id') );
			if( $request->query->get('act') == "red" ) {
				$message->setLu(1);
				$message->setDateLecture( new \DateTime('now') );
			} elseif( $request->query->get('act') == "unred" ) { $message->setLu(0); }
			$em->flush();
			
			$request->getSession()->getFlashBag()->add('notice', 'Votre message a bien été modifié.');
			
			return $this->redirectToRoute('lap_messagerie_homepage');
		}
		
		return $this->render('LAPMessagerieBundle:Default:index.html.twig', array(
			'name'                  => $util['name'],
			'surname'               => $util['surname'],
			'picture'               => $util['picture'],
			'mesmessages'           => $mesmessages,
            'listeLastmessagerie'   => $listes[0],
			'listeLast5notifs'      => $listes[1],
            'list5LastTasks'        => $listes[2]
		));
    }

	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
	public function viewmessageAction($id, Request $request)
	{
		/* Set message to "Lu" first */
		$em = $this->getDoctrine()->getManager();
		$message = $em->getRepository('LAPMessagerieBundle:Messagerie')->find( $id );
		$message->setLu(1);
		$message->setDateLecture( new \DateTime('now') );
		$em->flush();
	
		/* Call the Injection service */
		$inject = $this->container->get('lap_admin.inject');
		
		/* Connected User data */
		$u = $this->get('security.token_storage')->getToken()->getUser();
		$util = $inject->connectedUserdatas($u);
		
		$repository = $this->getDoctrine()->getManager()->getRepository('LAPMessagerieBundle:Messagerie');
		$message = $repository->findMessage($id);

        /* Request for the notifications in the header page */
        $listes = $inject->miniListes( $util['id'] );
		
		if(null === $message) {
			throw new NotFoundHttpException("Le message d'id ".$id." n'existe pas.");
		}
		
		return $this->render('LAPMessagerieBundle:Default:viewmessage.html.twig', array(
			'name'                  => $util['name'],
			'surname'               => $util['surname'],
			'picture'               => $util['picture'],
			'message'               => $message,
            'listeLastmessagerie'   => $listes[0],
            'listeLast5notifs'      => $listes[1],
            'list5LastTasks'        => $listes[2]
		));
	}

	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
	public function writeAction(Request $request)
	{
		/* Call the Injection service */
		$inject = $this->container->get('lap_admin.inject');
		
		/* Connected User data */
		$u = $this->get('security.token_storage')->getToken()->getUser();
		$util = $inject->connectedUserdatas($u);
		$usrid = $util['id'];
		
		$write = new Messagerie();
		$form = $this->createForm(MessagerieType::class, $write, array(
			'attr' => array('id' => $usrid)
		));
		
		$messageOriginal = 0;
		$aut = "";
		$idusrn = 0;
		if( $request->query->get('idmessoriginal') != "" ) {
			$repository = $this->getDoctrine()->getManager()->getRepository('LAPMessagerieBundle:Messagerie');
			$idm = $request->query->get('idmessoriginal');
			$aut = $request->query->get('auteur');
			$idusrn = $request->query->get('idusrn');
			$messageOriginal = $repository->findBy( array('id'=>  $idm) );
		}
		
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$write->setLu( 0 );
			$write->setDateReception( new \DateTime('now') );
			$write->setIdAuteur( $usrid );
			$write->setIdDestinataire( $write->getIdDestinataire()->getId() );
			$em = $this->getDoctrine()->getManager();
			$em->persist($write);
			$em->flush();
			
			$request->getSession()->getFlashBag()->add('notice', 'Votre message a bien été envoyé.');
			
			return $this->redirectToRoute('lap_messagerie_homepage');
		}

        /* Request for the notifications in the header page */
        $listes = $inject->miniListes( $util['id'] );
		
		return $this->render('LAPMessagerieBundle:Default:add.html.twig', array(
			'name'                  => $util['name'],
			'surname'               => $util['surname'],
			'picture'               => $util['picture'],
			'messageOriginal'       => $messageOriginal,
			'auteur'                => $aut,
			'idsurn'                => $idusrn,
			'listeLastmessagerie'   => $listes[0],
			'listeLast5notifs'      => $listes[1],
            'list5LastTasks'        => $listes[2],
			'form'                  => $form->createView()

		));
	}

	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
	public function deleteAction(Request $request, $id)
	{
		/* Call the Injection service */
		$inject = $this->container->get('lap_admin.inject');
		
		/* Connected User data */
		$u = $this->get('security.token_storage')->getToken()->getUser();
		$util = $inject->connectedUserdatas($u);
		
		$em = $this->getDoctrine()->getManager();
		$message = $em->getRepository('LAPMessagerieBundle:Messagerie')->find($id);
		
		if(null === $message) {
			throw new NotFoundHttpException("Le message d'id ".$id." n'existe pas.");
		}
		
		$form = $this->get('form.factory')->create();

        /* Request for the notifications in the header page */
        $listes = $inject->miniListes( $util['id'] );
		
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$em->remove($message);
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice', "Le message a bien été supprimé.");

			return $this->redirectToRoute('lap_messagerie_homepage');
		}
		
		return $this->render('LAPMessagerieBundle:Default:delete.html.twig', array(
			'name'                  => $util['name'],
			'surname'               => $util['surname'],
			'picture'               => $util['picture'],
			'message'               => $message,
			'listeLastmessagerie'   => $listes[0],
			'listeLast5notifs'      => $listes[1],
            'list5LastTasks'        => $listes[2],
			'form'                  => $form->createView()

		));
	}
}
