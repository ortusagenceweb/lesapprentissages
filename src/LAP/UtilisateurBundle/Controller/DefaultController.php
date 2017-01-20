<?php

namespace LAP\UtilisateurBundle\Controller;

use LAP\UtilisateurBundle\Entity\User;
use LAP\UtilisateurBundle\Form\UserType;
use LAP\UtilisateurBundle\Form\UseradmineditType;
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
		
		/* All users */
		$repository = $this->getDoctrine()->getManager()->getRepository('LAPUtilisateurBundle:User');
		$listeUsers = $repository->findAll();

        /* Request for the notifications in the header page */
        $listes = $inject->miniListes( $util['id'] );
		
		return $this->render('LAPUtilisateurBundle:Default:index.html.twig', array(
			'name'                  => $util['name'],
			'surname'               => $util['surname'],
			'picture'               => $util['picture'],
			'allusers'              => $listeUsers,
			'listeLastmessagerie'   => $listes[0],
			'listeLast5notifs'      => $listes[1],
            'list5LastTasks'        => $listes[2]
		));
    }
	
	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
	public function viewuserAction($id, Request $request, User $user)
	{
		/* Call the Injection service */
		$inject = $this->container->get('lap_admin.inject');
		
		/* Connected User data */
		$u = $this->get('security.token_storage')->getToken()->getUser();
		$util = $inject->connectedUserdatas($u);
		
		$form = $this->get('form.factory')->create(UseradmineditType::class, $user);

        /* Request for the notifications in the header page */
        $listes = $inject->miniListes( $util['id'] );
		
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$n = $form['roles']->getData()->getName();
			
			/*$p = $form->getData()->getPassword();
			/* Password encryption *
			$pass = $this->get('security.encoder_factory')->getEncoder($user)->encodePassword($p, $user->getSalt());
			$user->setPassword($pass);*/
			
			$user->setRoles(array('ROLE_'.$n));
			
			$em->flush();			
			$request->getSession()->getFlashBag()->add('notice', 'Le membre a bien été modifié.');
			
			return $this->redirectToRoute('lap_utilisateur_homepage');
		}
		
		return $this->render('LAPUtilisateurBundle:Default:edit.html.twig', array(
			'name'                  => $util['name'],
			'surname'               => $util['surname'],
			'picture'               => $util['picture'],
			'pictureu'              => $user->getPicture(),
			'usernameu'             => $user->getUsername(),
			'user'                  => $user,
            'listeLastmessagerie'   => $listes[0],
            'listeLast5notifs'      => $listes[1],
            'list5LastTasks'        => $listes[2],
            'form'                  => $form->createView()
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
		
		$user = new User();
		$form = $this->createForm(UserType::class, $user);

        /* Request for the notifications in the header page */
        $listes = $inject->miniListes( $util['id'] );
		
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$em = $this->getDoctrine()->getManager();
			
			$n = $form['roles']->getData()->getName();
			$p = $form['password']->getData();
			
			/* Password encryption */
			$pass = $this->get('security.encoder_factory')->getEncoder($user)->encodePassword($p, $user->getSalt());
			$user->setPassword($pass);
			
			$user->setRoles(array('ROLE_'.$n));
			
			$em->persist($user);
			$em->flush();			
			$request->getSession()->getFlashBag()->add('notice', 'Votre membre a bien été ajouté.');
			
			return $this->redirectToRoute('lap_utilisateur_homepage');
		}
		
		return $this->render('LAPUtilisateurBundle:Default:add.html.twig', array(
            'name'                  => $util['name'],
			'surname'               => $util['surname'],
			'picture'               => $util['picture'],
            'listeLastmessagerie'   => $listes[0],
            'listeLast5notifs'      => $listes[1],
            'list5LastTasks'        => $listes[2],
			'form'                  => $form->createView()
		));
	}
}
