<?php

namespace LAP\AdminBundle\Inject;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LAPInject extends Controller
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
	
	public function connectedUserdatas($u)
	{
		$name = $u->getName();
		$surname = $u->getSurname();
		$picture = $u->getPicture();
		$id = $u->getId();
		$datas = array(
			'name' => $name,
			'surname' => $surname,
			'picture' => $picture,
			'id' => $id
		);
		return $datas;
	}
	
	private function listeLastmessagerie($id)
	{
		/* 5 last messages in Messagerie */
		$repository = $this->em->getRepository('LAPMessagerieBundle:Messagerie');
		$listeLastmessagerie = $repository->findLastmessages($id, 5);
		return $listeLastmessagerie;
	}
	
	private function listeLastnotifications($limit)
	{
		/* $limit last notifications */
		$repository = $this->em->getRepository('LAPNotificationsBundle:Notifications');
		$listeLastnotifications = $repository->findLastnotifications($limit);
		return $listeLastnotifications;
	}
	
	public function listeAllnotifications()
	{
		/* All notifications */
		$repository = $this->em->getRepository('LAPNotificationsBundle:Notifications');
		$listeAllnotifications = $repository->findAllnotifications();
		return $listeAllnotifications;
	}
	
	public function lastCalendarid()
	{
		$repository = $this->em->getRepository('LAPCalendarBundle:Events');
		$lastCalendarid = $repository->findLastcalendarid();
		return $lastCalendarid;
	}

	public function miniListes($usrid)
    {
        $liste = array();
        /* 5 (max) last messages in Messagerie */
        $listeLastmessagerie = $this->listeLastmessagerie( $usrid );

        /* 5 (max) last notifications */
        $listeLast5notifs = $this->listeLastnotifications(5);

        /* 5 (max) last tasks */
        $repository = $this->em->getRepository('LAPTodoBundle:Todolist');
        $list5LastTasks = $repository->findAlltasks( $usrid, 5 );

        $liste[] = $listeLastmessagerie;
        $liste[] = $listeLast5notifs;
        $liste[] = $list5LastTasks;

        return $liste;
    }
}