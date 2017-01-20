<?php

namespace LAP\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class DefaultController extends Controller
{
	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
    public function indexAction()
    {
        $repository = $this
			->getDoctrine()
			->getManager()
			->getRepository('LAPBlogBundle:Article')
		;
		$listeArticles = $repository->findArticles();
		
		$ct = $this->container->get('lap_blog.closetags');

		foreach($listeArticles as $key => $article)
		{
			$txt = $listeArticles[$key]->getTexte();
			$txt_final = trim(substr($txt, 0, 700));
			$listeArticles[$key]->setTexte( $ct->closetags($txt_final."...") );
		}
		
		return $this->render('LAPBlogBundle:Default:index.html.twig', array('listeArticles' => $listeArticles));
    }

		/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
	public function viewAction($id)
	{
		$repository = $this->getDoctrine()->getManager()->getRepository('LAPBlogBundle:Article');
		$article = $repository->findOneBy(array('id' => $id));
		
		return $this->render('LAPBlogBundle:Default:view.html.twig', array('article' => $article));
	}
}
