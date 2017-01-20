<?php
namespace LAP\UtilisateurBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use LAP\UtilisateurBundle\Entity\User;

class LoadUser implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    // Les noms d'utilisateurs à créer
    $listNames = array('jeanpaul');

    foreach ($listNames as $name) {
      // On crée l'utilisateur
      $user = new User;

      $user->setUsername($name);
      $user->setPassword('123456');
      $user->setSalt('');
      $user->setRoles(array('ROLE_ADMIN'));	  
	  $user->setName('jean-paul');
	  $user->setSurname('bourillon');
	  $user->setPicture('images/membres/man.jpg');
	  $user->setActive(1);
	  $user->setDatecrea( new \DateTime() );
	  $user->setDatebirth( new \DateTime() );
	  $user->setPostalcode(14000);
	  $user->setAdress('rue des schtrumks');
	  $user->setCity('caen');
	  $user->setPhone('0607080910');
	  $user->setCountry('france');
	  $user->setDatelastconnexion( NULL );
	  $user->setEmail('jeanpaul.bourillon@gmail.com');

      // On le persiste
      $manager->persist($user);
    }

    // On déclenche l'enregistrement
    $manager->flush();
  }
}