<?php

namespace LAP\ContactBundle\Entity;
namespace LAP\ContactBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ContactRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ContactRepository extends \Doctrine\ORM\EntityRepository
{
	public function findMessages()
	{
		$qb = $this->createQueryBuilder('a');
		
		$qb
			//->where( 'a.traite != :traite' )
			//->setParameter( 'traite', 1 )
			->orderBy( 'a.dateContact', 'DESC' )
		;
		
		$query = $qb->getQuery();
		
		$results = $query->getResult();
		
		return $results;
	}
	
	public function findLastmessages($limit)
	{
		$qb = $this->createQueryBuilder('a');
		
		$qb
			//->where( 'a.traite != :traite' )
			//->setParameter( 'traite', 1 )
			->setMaxResults($limit)
			->orderBy( 'a.dateContact', 'DESC' )
		;
		
		$query = $qb->getQuery();
		
		$results = $query->getResult();
		
		return $results;
	}
	
	public function findMessage($id)
	{
		$query = $this->_em->createQuery('SELECT a.id AS id, a.dateContact AS dateContact, a.choix AS choix, a.nom AS nom, a.prenom AS prenom, a.email AS email, a.telephone AS telephone, a.texte AS texte, a.traite AS traite, a.dateTraite AS dateTraite, u.username AS username FROM LAPContactBundle:Contact a LEFT JOIN LAPUtilisateurBundle:User u WITH (a.idTraite = u.id) WHERE a.id = :id')
			->setParameter('id', $id)
			->setMaxResults(1);

		$results = $query->getOneOrNullResult();
		
		return $results;
	}
}
