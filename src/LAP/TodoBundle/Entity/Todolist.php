<?php

namespace LAP\TodoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Todolist
 *
 * @ORM\Table(name="todolist")
 * @ORM\Entity(repositoryClass="LAP\TodoBundle\Repository\TodolistRepository")
 */
class Todolist
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
     * @var int
     *
     * @ORM\Column(name="isdone", type="integer")
     */
    private $isdone;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCrea", type="datetime")
     */
    private $dateCrea;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFin", type="datetime")
     */
    private $dateFin;

    /**
     * @var int
     *
     * @ORM\Column(name="auteur", type="integer")
     */
    private $auteur;

    /**
     * @var int
     *
     * @ORM\Column(name="forall", type="integer", nullable=true)
     */
    private $forall;

	
	public function __construct() {
		$this->dateCrea = new \DateTime();
		$this->isdone = 0;
	}
	
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
     * Set isdone
     *
     * @param integer $isdone
     *
     * @return Todolist
     */
    public function setIsdone($isdone)
    {
        $this->isdone = $isdone;

        return $this;
    }

    /**
     * Get isdone
     *
     * @return int
     */
    public function getIsdone()
    {
        return $this->isdone;
    }

    /**
     * Set titre
     *
     * @param string $titre
     *
     * @return Todolist
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
     * Set dateCrea
     *
     * @param \DateTime $dateCrea
     *
     * @return Todolist
     */
    public function setDateCrea($dateCrea)
    {
        $this->dateCrea = $dateCrea;

        return $this;
    }

    /**
     * Get dateCrea
     *
     * @return \DateTime
     */
    public function getDateCrea()
    {
        return $this->dateCrea;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     *
     * @return Todolist
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * Set auteur
     *
     * @param integer $auteur
     *
     * @return Todolist
     */
    public function setAuteur($auteur)
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * Get auteur
     *
     * @return int
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * Set forall
     *
     * @param integer $forall
     *
     * @return Todolist
     */
    public function setForall($forall)
    {
        $this->forall = $forall;

        return $this;
    }

    /**
     * Get forall
     *
     * @return int
     */
    public function getForall()
    {
        return $this->forall;
    }
}

