<?php

namespace LAP\MessagerieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Messagerie
 *
 * @ORM\Table(name="messagerie")
 * @ORM\Entity(repositoryClass="LAP\MessagerieBundle\Repository\MessagerieRepository")
 */
class Messagerie
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="texte", type="text")
     */
    private $texte;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateReception", type="datetime")
     */
    private $dateReception;

    /**
     * @var bool
     *
     * @ORM\Column(name="lu", type="boolean")
     */
    private $lu;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateLecture", type="datetime", nullable=true)
     */
    private $dateLecture;

    /**
     * @var int
     *
     * @ORM\Column(name="idAuteur", type="integer")
     */
    private $idAuteur;
	
	/**
     * @var int
     *
     * @ORM\Column(name="idDestinataire", type="integer")
     */
    private $idDestinataire;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set titre
     *
     * @param string $titre
     *
     * @return Messagerie
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set texte
     *
     * @param string $texte
     *
     * @return Messagerie
     */
    public function setTexte($texte)
    {
        $this->texte = $texte;

        return $this;
    }

    /**
     * Get texte
     *
     * @return string
     */
    public function getTexte()
    {
        return $this->texte;
    }

    /**
     * Set dateReception
     *
     * @param \DateTime $dateReception
     *
     * @return Messagerie
     */
    public function setDateReception($dateReception)
    {
        $this->dateReception = $dateReception;

        return $this;
    }

    /**
     * Get dateReception
     *
     * @return \DateTime
     */
    public function getDateReception()
    {
        return $this->dateReception;
    }

    /**
     * Set lu
     *
     * @param boolean $lu
     *
     * @return Messagerie
     */
    public function setLu($lu)
    {
        $this->lu = $lu;

        return $this;
    }

    /**
     * Get lu
     *
     * @return bool
     */
    public function getLu()
    {
        return $this->lu;
    }

    /**
     * Set dateLecture
     *
     * @param \DateTime $dateLecture
     *
     * @return Messagerie
     */
    public function setDateLecture($dateLecture)
    {
        $this->dateLecture = $dateLecture;

        return $this;
    }

    /**
     * Get dateLecture
     *
     * @return \DateTime
     */
    public function getDateLecture()
    {
        return $this->dateLecture;
    }

    /**
     * Set idAuteur
     *
     * @param integer $idAuteur
     *
     * @return Messagerie
     */
    public function setIdAuteur($idAuteur)
    {
        $this->idAuteur = $idAuteur;

        return $this;
    }

    /**
     * Get idAuteur
     *
     * @return int
     */
    public function getIdAuteur()
    {
        return $this->idAuteur;
    }

    /**
     * Set idDestinataire
     *
     * @param integer $idDestinataire
     *
     * @return Messagerie
     */
    public function setIdDestinataire($idDestinataire)
    {
        $this->idDestinataire = $idDestinataire;

        return $this;
    }

    /**
     * Get idDestinataire
     *
     * @return integer
     */
    public function getIdDestinataire()
    {
        return $this->idDestinataire;
    }
}
