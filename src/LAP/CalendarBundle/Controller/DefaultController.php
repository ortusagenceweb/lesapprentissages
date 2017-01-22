<?php

namespace LAP\CalendarBundle\Controller;

use LAP\CalendarBundle\Entity\Events;
use LAP\CalendarBundle\Form\EventType;
use LAP\CalendarBundle\Form\EventEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DefaultController extends Controller
{
	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
	public function indexAction(Request $request, $route=null)
    {
		/* Call the Injection service */
		$inject = $this->container->get('lap_admin.inject');
		
		/* Connected User data */
		$u = $this->get('security.token_storage')->getToken()->getUser();
		$util = $inject->connectedUserdatas($u);

        /* Request for the notifications in the header page */
        $listes = $inject->miniListes( $util['id'] );
		
		$repository = $this->getDoctrine()->getManager()->getRepository('LAPCalendarBundle:Events');
		$ev = $repository->findAllCalendar();

        $tb = $inject->formatJsonTab($ev);
        if($request->isXMLHttpRequest()) {
            return new JsonResponse( $tb, 200 );
        }

		return $this->render('LAPCalendarBundle:Default:index.html.twig', array(
			'name'                  => $util['name'],
			'surname'               => $util['surname'],
			'picture'               => $util['picture'],
			'listeLastmessagerie'   => $listes[0],
			'listeLast5notifs'      => $listes[1],
            'list5LastTasks'        => $listes[2]
		));
	}
	
	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
	public function vieweventAction($id, Request $request)
	{
		/* Call the Injection service */
		$inject = $this->container->get('lap_admin.inject');
		
		/* Connected User data */
		$u = $this->get('security.token_storage')->getToken()->getUser();
		$util = $inject->connectedUserdatas($u);
		
		$em = $this->getDoctrine()->getManager();
		$event = $em->getRepository('LAPCalendarBundle:Events')->findEvent($id);
		
		if(null === $event) {
			throw new NotFoundHttpException("L'évènement d'id ".$id." n'existe pas.");
		}

        /* Request for the notifications in the header page */
        $listes = $inject->miniListes( $util['id'] );
		
		return $this->render('LAPCalendarBundle:Default:viewevent.html.twig', array(
			'name'                  => $util['name'],
			'surname'               => $util['surname'],
			'picture'               => $util['picture'],
			'listeLastmessagerie'   => $listes[0],
			'listeLast5notifs'      => $listes[1],
            'list5LastTasks'        => $listes[2],
			'event'                 => $event

		));
	}
	
	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
	public function addeventAction(Request $request)
	{	
		/* Call to the Injection service */
		$inject = $this->container->get('lap_admin.inject');

        /* Connected User data */
        $u = $this->get('security.token_storage')->getToken()->getUser();
        $util = $inject->connectedUserdatas($u);
		
		$event = new Events();
		$form = $this->createForm(EventType::class, $event);

        /* Request for the notifications in the header page */
        $listes = $inject->miniListes( $util['id'] );
		
		/* Last ID in the Events database */
		$lastCalendarid = $inject->lastCalendarid();
		$lastId = $lastCalendarid->getId();
		$lastId += 1;

		/* Absolute URL */
		$r = $this->generateUrl('lap_admin_homepage', array(), UrlGeneratorInterface::ABSOLUTE_URL);
		$url = $r.'/calendar/viewevent/'.$lastId;

		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {		
			$em = $this->getDoctrine()->getManager();
			
			$em->persist($event);
			$em->flush();			
			$request->getSession()->getFlashBag()->add('notice', 'Votre évènement a bien été ajouté.');
			
			return $this->redirectToRoute('lap_calendar_homepage');
		}
		
		return $this->render('LAPCalendarBundle:Default:addevent.html.twig', array(
			'name'                  => $util['name'],
			'surname'               => $util['surname'],
			'idusr'                 => $idusr,
			'picture'               => $util['picture'],
			'listeLastmessagerie'   => $listes[0],
			'listeLast5notifs'      => $listes[1],
            'list5LastTasks'        => $listes[2],
			'url'                   => $url,
			'form'                  => $form->createView()

		));
	}
	
	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
	public function editeventAction($id, Request $request, Events $event)
	{
		/* Call the Injection service */
		$inject = $this->container->get('lap_admin.inject');

        /* Connected User data */
        $u = $this->get('security.token_storage')->getToken()->getUser();
        $util = $inject->connectedUserdatas($u);
		
		$form = $this->get('form.factory')->create(EventEditType::class, $event);

        /* Request for the notifications in the header page */
        $listes = $inject->miniListes( $util['id'] );
		
		/* Absolute URL */
		$r = $this->generateUrl('lap_admin_homepage', array(), UrlGeneratorInterface::ABSOLUTE_URL);
		$url = $r.'/calendar/viewevent/'.$id;
		
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
			$em->flush();			
			$request->getSession()->getFlashBag()->add('notice', 'Votre évènement a bien été modifié.');			
			return $this->redirectToRoute('lap_calendar_viewevent', array('id' => $id));
		}
		
		return $this->render('LAPCalendarBundle:Default:editevent.html.twig', array(
			'name' => $util['name'],
			'surname'               => $util['surname'],
			'picture'               => $util['picture'],
			'event'                 => $event,
			'idusr'                 => $idusr,
			'url'                   => $url,
			'listeLastmessagerie'   => $listes[0],
			'listeLast5notifs'      => $listes[1],
            'list5LastTasks'        => $listes[2],
			'form'                  => $form->createView()

		));
	}
	
	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
	public function deleteeventAction(Request $request, $id)
	{
		/* Call the Injection service */
		$inject = $this->container->get('lap_admin.inject');
		
		/* Connected User data */
		$u = $this->get('security.token_storage')->getToken()->getUser();
		$util = $inject->connectedUserdatas($u);
		
		$em = $this->getDoctrine()->getManager();
		$event = $em->getRepository('LAPCalendarBundle:Events')->find($id);
		
		if(null === $event) {
			throw new NotFoundHttpException("L'évènement d'id ".$id." n'existe pas.");
		}
		
		$form = $this->get('form.factory')->create();

        /* Request for the notifications in the header page */
        $listes = $inject->miniListes( $util['id'] );
		
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$em->remove($event);
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice', "L'évènement a bien été supprimé.");

			return $this->redirectToRoute('lap_calendar_homepage');
		}
		
		return $this->render('LAPCalendarBundle:Default:deleteevent.html.twig', array(
			'name'                  => $util['name'],
			'surname'               => $util['surname'],
			'picture'               => $util['picture'],
			'event'                 => $event,
			'listeLastmessagerie'   => $listes[0],
			'listeLast5notifs'      => $listes[1],
            'list5LastTasks'        => $listes[2],
			'form'                  => $form->createView()
		));
	}
}
