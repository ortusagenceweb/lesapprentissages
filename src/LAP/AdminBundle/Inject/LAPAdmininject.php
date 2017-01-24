<?php

namespace LAP\AdminBundle\Inject;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LAPAdmininject extends Controller
{
	/**
	*
	* Inject datas for all the functions
	*
	*/
	protected $em;
	
	public function __construct(\Doctrine\ORM\EntityManager $em)
	{
		$this->em = $em;
	}

    public function recupviewmessage($id)
    {
        $repository = $this->em->getRepository('LAPContactBundle:Contact');
        $message = $repository->findMessage($id);

        if(null === $message) {
            throw new NotFoundHttpException("Le message d'id ".$id." n'existe pas.");
        }

        return $message;
    }

    public function recupusr($userid)
    {
        $repository = $this->em->getRepository('LAPUtilisateurBundle:User');
        $usr = $repository->find($userid);

        if(null === $usr) {
            throw new NotFoundHttpException("L'utilisateur d'id ".$id." n'existe pas.");
        }

        return $usr;
    }

    public function recuparticle($id)
    {
        $repository = $this->em->getRepository('LAPBlogBundle:Article');
        $article = $repository->find($id);

        if(null === $article) {
            throw new NotFoundHttpException("L'article d'id ".$id." n'existe pas.");
        }

        return $article;
    }

    public function recuptask($id)
    {
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('LAPTodoBundle:Todolist')->find($id);

        if(null === $task) {
            throw new NotFoundHttpException("La tâche d'id ".$id." n'existe pas.");
        }

        return $task;
    }
}