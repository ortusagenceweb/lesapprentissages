<?php

namespace LAP\AdminBundle\Login;

use LAP\UtilisateurBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginsuccessListener
{
	/**
	* @var \Doctrine\ORM\EntityManager
	*/
	private $entityManager;
	
	public function __construct(\Doctrine\ORM\EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	public function onLoginSuccess(InteractiveLoginEvent $event)
	{
		$user = $event->getAuthenticationToken()->getUser();
		$lastconnex = $user->getDateLastconnexion();
		$usrid = $user->getId();
		
		$em = $this->entityManager;
		$usr = $em->getRepository('LAPUtilisateurBundle:User')->find( $usrid );
		$usr->setDatelastconnexion( new \DateTime('now') );
		$em->flush();
	}
}