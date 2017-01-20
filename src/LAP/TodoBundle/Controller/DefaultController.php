<?php

namespace LAP\TodoBundle\Controller;

use LAP\TodoBundle\Entity\Todolist;
use LAP\TodoBundle\Form\TodoType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

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
		
		/* All tasks */
		$repository = $this->getDoctrine()->getManager()->getRepository('LAPTodoBundle:Todolist');
		$listAllTasks = $repository->findAlltasks( $util['id'], 0 );

        /* Request for the notifications in the header page */
        $listes = $inject->miniListes( $util['id'] );
		
		if( $request->isXmlHttpRequest() ) {
		    $data = array();
            $em = $this->getDoctrine()->getManager();
            $article = $em->getRepository('LAPTodoBundle:Todolist')->find( $request->request->get('var') );
            $doneOrnot = $article->getIsdone();
            if($doneOrnot == 0) {
                $article->setIsdone(1);
            } else {
                $article->setIsdone(0);
            }
            $data['div'] = $request->request->get('var');
            $em->flush();
            return new JsonResponse( $data, 200 );

			/*$task = $repository->findBy( array('id' => $request->request->get('var')) );
            $encoders = array(new XmlEncoder(), new JsonEncoder());
            $normalizers = array(new ObjectNormalizer());
            $serializer = new Serializer($normalizers, $encoders);
            $jsonContent = $serializer->serialize( $task, 'json');
			return new JsonResponse( json_encode( $jsonContent, 200 ));*/
		}
		
        return $this->render('LAPTodoBundle:Default:index.html.twig', array(
			'name'                  => $util['name'],
			'surname'               => $util['surname'],
			'picture'               => $util['picture'],
            'listAllTasks'          => $listAllTasks,
			'listeLastmessagerie'   => $listes[0],
			'listeLast5notifs'      => $listes[1],
            'list5LastTasks'        => $listes[2]
		));
    }
	
	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
	public function addtaskAction(Request $request)
	{	
		/* Call to the Injection service */
		$inject = $this->container->get('lap_admin.inject');
		
		/* Connected User data */
		$u = $this->get('security.token_storage')->getToken()->getUser();
		$util = $inject->connectedUserdatas($u);
		$idusr = $util['id'];
		
		$task = new Todolist();
		$form = $this->createForm(TodoType::class, $task);

        /* Request for the notifications in the header page */
        $listes = $inject->miniListes( $util['id'] );
		
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {			
			$em = $this->getDoctrine()->getManager();
			$em->persist($task);
			$em->flush();
			
			$request->getSession()->getFlashBag()->add('notice', 'Votre tâche a bien été postée.');
			
			return $this->redirectToRoute('lap_todo_homepage');
		}
		
		return $this->render('LAPTodoBundle:Default:addtask.html.twig', array(
			'name'                  => $util['name'],
			'surname'               => $util['surname'],
			'picture'               => $util['picture'],
			'idusr'                 => $idusr,
			'listeLastmessagerie'   => $listes[0],
			'listeLast5notifs'      => $listes[1],
            'list5LastTasks'        => $listes[2],
            'form'                  => $form->createView()
		));
	}
	
	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
	public function deletetaskAction(Request $request, $id)
	{
		/* Call the Injection service */
		$inject = $this->container->get('lap_admin.inject');
		
		/* Connected User data */
		$u = $this->get('security.token_storage')->getToken()->getUser();
		$util = $inject->connectedUserdatas($u);
		
		$task = $admininject->recuptask($id);
		
		$form = $this->get('form.factory')->create();

        /* Request for the notifications in the header page */
        $listes = $inject->miniListes( $util['id'] );
		
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$em->remove($task);
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice', "La tâche a bien été supprimé.");

			return $this->redirectToRoute('lap_todo_homepage');
		}
		
		return $this->render('LAPTodoBundle:Default:deletetask.html.twig', array(
			'name'                  => $util['name'],
			'surname'               => $util['surname'],
			'picture'               => $util['picture'],
			'task'                  => $task,
            'listeLastmessagerie'   => $listes[0],
            'listeLast5notifs'      => $listes[1],
            'list5LastTasks'        => $listes[2],
			'form'                  => $form->createView()
		));
	}
}
