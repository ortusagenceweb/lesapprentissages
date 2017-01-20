<?php

namespace LAP\NotificationsBundle\Controller;

use LAP\NotificationsBundle\Entity\Notifications;
use LAP\NotificationsBundle\Form\NotificationsType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
    public function indexAction(Request $request)
    {
		/* Call the Injection service */
		$inject = $this->container->get('lap_admin.inject');

        /* Getting the current route */
        $r = $this->generateUrl('lap_notifications_homepage', array(), UrlGeneratorInterface::ABSOLUTE_URL);
        $route = explode("/", $r);
        $route = end($route);
		
		/* Connected User data */
		$u = $this->get('security.token_storage')->getToken()->getUser();
		$util = $inject->connectedUserdatas($u);
		
		/* 5 (max) last messages in Messagerie */
		$listeLastmessagerie = $inject->listeLastmessagerie( $util['id'] );
		
		/* 5 (max) last notifications */
		$listeLastnotifications = $inject->listeLastnotifications(5);
		
		/* All notifications */
		$listeAllnotifications = $inject->listeAllnotifications();

        /* Request for the notifications in the header page */
        $listes = $inject->miniListes( $util['id'] );
		
		/* Formatting text for viewing in notifications homepage table */
		$t = $this->container->get('lap_admin.cuttxt');
		foreach($listeAllnotifications as $key => $notif)
		{
			$txt = $listeAllnotifications[$key]['texte'];
			$txtcuted = $t->cuttext($txt);
			$listeAllnotifications[$key]['texte'] = ( trim(strip_tags(substr($txtcuted, 0, 80))) );
		}
		
		return $this->render('LAPNotificationsBundle:Default:index.html.twig', array(
			'name'                      => $util['name'],
			'surname'                   => $util['surname'],
			'picture'                   => $util['picture'],
			'listeLastnotifications'    => $listeLastnotifications,
			'listeAllnotifications'     => $listeAllnotifications,
            'listeLastmessagerie'       => $listes[0],
			'listeLast5notifs'          => $listes[1],
            'list5LastTasks'            => $listes[2],
            'route'                     => $route
		));
    }

	/**
	* @Security("has_role('ROLE_ADMIN')")
    * @ParamConverter("post", class="LAPNotificationsBundle:Notifications", options={"repository_method" = "findNotification"})
	*/
	public function viewnotifAction($id, Request $request)
	{
		/* Call the Injection service */
		$inject = $this->container->get('lap_admin.inject');
		
		/* Connected User data */
		$u = $this->get('security.token_storage')->getToken()->getUser();
		$util = $inject->connectedUserdatas($u);
		
		/* One notification by id */
		$repository = $this->getDoctrine()->getManager()->getRepository('LAPNotificationsBundle:Notifications');
		$notif = $repository->findNotification($id);

		if(null === $notif) {
			throw new NotFoundHttpException("La notifiaction d'id ".$id." n'existe pas.");
		}

		/* All notifications status*/
		$repository1 = $this->getDoctrine()->getManager()->getRepository('LAPNotificationsBundle:Notificationsstatut');
		$status = $repository1->findAll();

        /* Request for the notifications in the header page */
        $listes = $inject->miniListes( $util['id'] );
		
		/* Modifying status value directly from viewnotification page */
		if ($request->isMethod('POST')) {
			//die(var_dump($request->query->get('act')));
			$em = $this->getDoctrine()->getManager();
			$notif = $em->getRepository('LAPNotificationsBundle:Notifications')->find( $id );
			switch( $request->query->get('act') )
			{
				case 'statut-1':
					$notif->setStatut(1);
					break;
				case 'statut-2':
					$notif->setStatut(2);
					break;
				case 'statut-3':
					$notif->setStatut(3);
					break;
				case 'statut-4':
					$notif->setStatut(4);
					break;
				case 'statut-5':
					$notif->setStatut(5);
					break;
			}
			$em->flush();
			
			$request->getSession()->getFlashBag()->add('notice', 'La notification a bien été modifiée.');
			
			return $this->redirectToRoute('lap_notifications_viewnotif', array('id' => $id));
		}
		
		return $this->render('LAPNotificationsBundle:Default:viewnotif.html.twig', array(
			'name'                  => $util['name'],
			'surname'               => $util['surname'],
			'picture'               => $util['picture'],
			'notif'                 => $notif,
			'status'                => $status,
            'listeLastmessagerie'   => $listes[0],
            'listeLast5notifs'      => $listes[1],
            'list5LastTasks'        => $listes[2]
		));
	}

	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
	public function addAction(Request $request)
	{
		/* Call to the Injection service */
		$inject = $this->container->get('lap_admin.inject');
		
		/* Connected User data */
		$u = $this->get('security.token_storage')->getToken()->getUser();
		$util = $inject->connectedUserdatas($u);
		
		$notif = new Notifications();
		$form = $this->createForm(NotificationsType::class, $notif);

        /* Request for the notifications in the header page */
        $listes = $inject->miniListes( $util['id'] );
		
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$notif->setStatut( $notif->getStatut()->getId() );
			$em = $this->getDoctrine()->getManager();
			$em->persist($notif);
			$em->flush();
			
			$request->getSession()->getFlashBag()->add('notice', 'Votre notification a bien été ajoutée.');
			
			return $this->redirectToRoute('lap_notifications_homepage');
		}
		
		return $this->render('LAPNotificationsBundle:Default:add.html.twig', array(
			'name'                  => $util['name'],
			'surname'               => $util['surname'],
			'picture'               => $util['picture'],
			'idusr'                 => $util['id'],
			'listeLastmessagerie'   => $listes[0],
			'listeLast5notifs'      => $listes[1],
            'list5LastTasks'        => $listes[2],
			'form'                  => $form->createView()

		));
	}
}
