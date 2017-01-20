<?php

namespace LAP\EquipeBundle\Entity;
namespace LAP\EquipeBundle\Repository;

namespace LAP\EquipeBundle\Repository;

/**
 * MembresRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MembresRepository extends \Doctrine\ORM\EntityRepository
{
	public function findMembres()
	{
		$qb = $this->createQueryBuilder('a');
		
		$qb
			->where( 'a.active = :isActive' )
			->setParameter( 'isActive', 1 )
		;
		
		$query = $qb->getQuery();
		
		$results = $query->getResult();
		
		return $results;
	}
}
