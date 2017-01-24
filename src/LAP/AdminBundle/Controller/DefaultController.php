<?php

namespace LAP\AdminBundle\Controller;

use LAP\BlogBundle\Entity\Article;
use LAP\ContactBundle\Entity\Contact;
use LAP\BlogBundle\Form\ArticleType;
use LAP\BlogBundle\Form\ArticleEditType;
use LAP\UtilisateurBundle\Form\UsereditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class DefaultController extends Controller
{
    /**
	* @Security("has_role('ROLE_ADMIN')")
	*/
	public function indexAction(Request $request)
    {
        /* Call the Injection service */
        $inject = $this->container->get('lap_admin.inject');
        $t      = $this->container->get('lap_admin.cuttxt');

		/* Connected User data */
		$u      = $this->get('security.token_storage')->getToken()->getUser();
		$util   = $inject->connectedUserdatas($u);

        /* Request for the notifications in the header page */
        $listes                 = $inject->miniListes( $util['id'] );
        $listeLastmessagerie    = $listes[0];
		
		/* All users */
		$repository = $this->getDoctrine()->getManager()->getRepository('LAPUtilisateurBundle:User');
		$listeUsers = $repository->findAllusers();
		
		/* All articles */
		$repository     = $this->getDoctrine()->getManager()->getRepository('LAPBlogBundle:Article');
		$listeArticles  = $repository->findAllarticles(10);
		
		/* 10 Last messages */
		$repository1        = $this->getDoctrine()->getManager()->getRepository('LAPContactBundle:Contact');
		$listeLastmessages  = $repository1->findLastmessages(10);

        $inject->formatTable($listeArticles, $t);
		$inject->limit45($listeLastmessagerie, $t);
		$inject->limit80($listeLastmessages, $t);

        if ($request->isMethod('POST')) {
            $inject->modifArticle($request);
            return $this->redirectToRoute('lap_admin_homepage');
        }
		
		return $this->render('LAPAdminBundle:Default:index.html.twig', array(
			'name'                  => $util['name'],
			'surname'               => $util['surname'],
			'picture'               => $util['picture'],
			'allusers'              => $listeUsers,
			'listeArticles'         => $listeArticles,
			'listLastmessages'      => $listeLastmessages,
			'listeLastmessagerie'   => $listes[0],
			'listeLast5notifs'      => $listes[1],
            'list5LastTasks'        => $listes[2]
		));
    }

	/**
	* @Security("has_role('ROLE_ADMIN')")
    * @Route("/admin/add", name="lap_admin_add")
	*/
	public function addAction(Request $request)
	{	
		/* Call to the Injection service */
		$inject = $this->container->get('lap_admin.inject');
		
		/* Connected User data */
		$u = $this->get('security.token_storage')->getToken()->getUser();
		$util = $inject->connectedUserdatas($u);
		
		$article = new Article();
		$form = $this->get('form.factory')->create(ArticleType::class, $article);

        /* Request for the notifications in the header page */
        $listes = $inject->miniListes( $util['id'] );
		
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			/* Email sending */
			/*$titre = $form["titre"]->getData();
			$auteur = $form["auteur"]->getData();
			$dateCrea = $form["dateCrea"]->getData();
			$datePub = $form["datePub"]->getData();
			$active = $form["active"]->getData();
			$texte = $form["texte"]->getData();
			$image = $form["image"]->getData();

			$name = $auteur;
			$message = \Swift_Message::newInstance()
				->setSubject('Un article a été posté')
				->setFrom('contact@ecoledesapprentissages.fr')
				->setTo('contact@ecoledesapprentissages.fr')
				->setBody(
					$this->renderView(
						'Email/articleposte.html.twig',
						array( 'titre' => $titre, 'auteur' => $auteur, 'dateCrea' => $dateCrea, 'texte' => $texte )
					),
					'text/html',
					'iso-8859-2'
				)
			;
			$this->get('mailer')->send($message);*/
			
			$em = $this->getDoctrine()->getManager();
			$em->persist($article);
			$em->flush();
			
			$request->getSession()->getFlashBag()->add('notice', 'Votre article a bien été posté.');
			
			return $this->redirectToRoute('lap_admin_homepage');
		}
		
		return $this->render('LAPAdminBundle:Default:add.html.twig', array(
			'name'                  => $util['name'],
			'surname'               => $util['surname'],
			'picture'               => $util['picture'],
			'listeLastmessagerie'   => $listes[0],
			'listeLast5notifs'      => $listes[1],
            'list5LastTasks'        => $listes[2],
			'form'                  => $form->createView()
		));
	}

	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
	public function editAction($id, Request $request)
	{
		/* Call the Injection service */
		$inject = $this->container->get('lap_admin.inject');
		$adminInject = $this->container->get('lap_admin.admininject');
		
		/* Connected User data */
		$u = $this->get('security.token_storage')->getToken()->getUser();
		$util = $inject->connectedUserdatas($u);

		$article = $adminInject->recuparticle($id);
		
		$form = $this->get('form.factory')->create(ArticleEditType::class, $article);

        /* Request for the notifications in the header page */
        $listes = $inject->miniListes( $util['id'] );
		
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$em->flush();
			$request->getSession()->getFlashBag()->add('notice', 'Votre article a bien été modifié.');
			return $this->redirectToRoute('lap_admin_homepage');
		}
		
		return $this->render('LAPAdminBundle:Default:edit.html.twig', array(
			'name'                  => $util['name'],
			'surname'               => $util['surname'],
			'picture'               => $util['picture'],
			'article'               => $article,
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
        $adminInject = $this->container->get('lap_admin.admininject');
		
		/* Connected User data */
		$u = $this->get('security.token_storage')->getToken()->getUser();
		$util = $inject->connectedUserdatas($u);

        $article = $adminInject->recuparticle($id);
		
		$form = $this->get('form.factory')->create();

        /* Request for the notifications in the header page */
        $listes = $inject->miniListes( $util['id'] );
		
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$em->remove($article);
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice', "L'article a bien été supprimé.");

			return $this->redirectToRoute('lap_admin_homepage');
		}
		
		return $this->render('LAPAdminBundle:Default:delete.html.twig', array(
			'name'                  => $util['name'],
			'surname'               => $util['surname'],
			'picture'               => $util['picture'],
			'article'               => $article,
			'listeLastmessagerie'   => $listes[0],
			'listeLast5notifs'      => $listes[1],
            'list5LastTasks'        => $listes[2],
			'form'                  => $form->createView()
        ));
	}

	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
	public function viewarticlesAction(Request $request)
	{
		/* Call the Injection service */
		$inject = $this->container->get('lap_admin.inject');
		
		/* Connected User data */
		$u = $this->get('security.token_storage')->getToken()->getUser();
		$util = $inject->connectedUserdatas($u);
		
		/* All users */
		$repository = $this->getDoctrine()->getManager()->getRepository('LAPUtilisateurBundle:User');
		$listeUsers = $repository->findAllusers();
		
		/* All articles */
		$repository = $this->getDoctrine()->getManager()->getRepository('LAPBlogBundle:Article');
		$listeArticles = $repository->findAll();
		
		/* Formatting text for viewing in admin homepage table */
		$t = $this->container->get('lap_admin.cuttxt');
		foreach($listeArticles as $key => $article)
		{
			$txt = $listeArticles[$key]->getTexte();
			$txtcuted = $t->cuttext($txt);
			$listeArticles[$key]->setTexte( trim(strip_tags(substr($txtcuted, 0, 80))) );
		}

        /* Request for the notifications in the header page */
        $listes = $inject->miniListes( $util['id'] );
		
		if ($request->isMethod('POST')) {
			$inject->modifArticle($request);
			return $this->redirectToRoute('lap_admin_viewarticles');
		}
		
		return $this->render('LAPAdminBundle:Default:viewarticles.html.twig', array(
			'name'                  => $util['name'],
			'surname'               => $util['surname'],
			'picture'               => $util['picture'],
			'allusers'              => $listeUsers,
			'listeLastmessagerie'   => $listes[0],
            'listeLast5notifs'      => $listes[1],
            'list5LastTasks'        => $listes[2],
			'listeArticles'         => $listeArticles
		));
	}

	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
	public function viewmessageAction($id, Request $request)
	{
		/* Call the Injection service */
		$inject = $this->container->get('lap_admin.inject');
		$adminInject = $this->container->get('lap_admin.admininject');

		/* Connected User data */
		$u = $this->get('security.token_storage')->getToken()->getUser();
		$util = $inject->connectedUserdatas($u);

		$message = $adminInject->recupviewmessage($id);

        /* Request for the notifications in the header page */
        $listes = $inject->miniListes( $util['id'] );
		
		/* Setting traite value to 1 (true) directly from viewmessage page */
		if ($request->isMethod('POST')) {
			$em = $this->getDoctrine()->getManager();
			$message = $em->getRepository('LAPContactBundle:Contact')->find( $id );
			if( $request->query->get('act') == "traite" ) {
				$message->setTraite(1);
				$message->setDateTraite( new \DateTime('now') );
				$message->setIdTraite( $userid );
			}
			$em->flush();
			
			$request->getSession()->getFlashBag()->add('notice', 'Le message a bien été marqué comme traité.');
			
			return $this->redirectToRoute('lap_admin_homepage');
		}
		
		return $this->render('LAPAdminBundle:Default:viewmessage.html.twig', array(
			'name'                  => $util['name'],
			'surname'               => $util['surname'],
			'picture'               => $util['picture'],
			'listeLastmessagerie'   => $listes[0],
			'listeLast5notifs'      => $listes[1],
            'list5LastTasks'        => $listes[2],
			'message'               => $message
		));
	}

	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
	public function profileAction(Request $request)
	{
		/* Call the Injection service */
		$inject = $this->container->get('lap_admin.inject');
		$adminInject = $this->container->get('lap_admin.admininject');
		
		/* Connected User data */
        $u      = $this->get('security.token_storage')->getToken()->getUser();
        $util   = $inject->connectedUserdatas($u);
		
        $usr = $adminInject->recupusr( $util['id'] );

        /* Request for the notifications in the header page */
        $listes = $inject->miniListes( $util['id'] );
		
		return $this->render('LAPAdminBundle:Default:profile.html.twig', array(
			'name'                  => $util['name'],
			'surname'               => $util['surname'],
			'picture'               => $util['picture'],
			'listeLastmessagerie'   => $listes[0],
			'listeLast5notifs'      => $listes[1],
            'list5LastTasks'        => $listes[2],
			'usr'                   => $usr
		));
	}

	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
	public function editprofileAction($id, Request $request)
	{
		/* Call the Injection service */
		$inject = $this->container->get('lap_admin.inject');
        $adminIject = $this->container->get('lap_admin.admininject');

		/* Connected User data */
		$u = $this->get('security.token_storage')->getToken()->getUser();
		$util = $inject->connectedUserdatas($u);

        $usr = $adminIject->recupusr($util['id']);

        /* Request for the notifications in the header page */
        $listes = $inject->miniListes( $util['id'] );
		
		$form = $this->get('form.factory')->create(UsereditType::class, $usr);
		
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$em->flush();
			$request->getSession()->getFlashBag()->add('notice', 'Votre profil a bien été modifié.');
			return $this->redirectToRoute('lap_admin_profile');
		}
		
		return $this->render('LAPAdminBundle:Default:editprofile.html.twig', array(
			'name'                  => $util['name'],
			'surname'               => $util['surname'],
			'picture'               => $util['picture'],
			'usr'                   => $usr,
			'listeLastmessagerie'   => $listes[0],
			'listeLast5notifs'      => $listes[1],
            'list5LastTasks'        => $listes[2],
			'form'                  => $form->createView()
		));
	}
}