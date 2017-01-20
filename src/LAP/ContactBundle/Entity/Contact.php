<?php

namespace LAP\ContactBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contact
 *
 * @ORM\Table(name="contact")
 * @ORM\Entity(repositoryClass="LAP\ContactBundle\Repository\ContactRepository")
 */
class Contact
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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var int
     *
     * @ORM\Column(name="telephone", type="integer")
     */
    private $telephone;

    /**
     * @var string
     *
     * @ORM\Column(name="texte", type="text")
     */
    private $texte;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateContact", type="datetime")
     */
    private $dateContact;

    /**
     * @var string
     *
     * @ORM\Column(name="choix", type="text")
     */
    private $choix;
	
	/**
     * @var integer
     *
     * @ORM\Column(name="traite", type="integer")
     */
    private $traite;
	
	/**
     * @var \DateTime
     *
     * @ORM\Column(name="dateTraite", type="datetime", nullable=true)
     */
    private $dateTraite;
	
	/**
     * @var integer
     *
     * @ORM\Column(name="idTraite", type="integer")
	 * @ORM\OneToOne(targetEntity="LAP\UtilisateurBundle\Entity\User", cascade={"persist"})
     */
    private $idTraite;
	
	public function __construct()
	{
		$this->dateContact = new \DateTime();
		$this->traite = 0;
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
     * Set nom
     *
     * @param string $nom
     *
     * @return Contact
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Contact
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Contact
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set telephone
     *
     * @param integer $telephone
     *
     * @return Contact
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return int
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set texte
     *
     * @param string $texte
     *
     * @return Contact
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
     * Set dateContact
     *
     * @param \DateTime $dateContact
     *
     * @return Contact
     */
    public function setDateContact($dateContact)
    {
        $this->dateContact = $dateContact;

        return $this;
    }

    /**
     * Get dateContact
     *
     * @return \DateTime
     */
    public function getDateContact()
    {
        return $this->dateContact;
    }

    /**
     * Set choix
     *
     * @param string $choix
     *
     * @return Contact
     */
    public function setChoix($choix)
    {
        $this->choix = $choix;

        return $this;
    }

    /**
     * Get choix
     *
     * @return string
     */
    public function getChoix()
    {
        return $this->choix;
    }

    /**
     * Set traite
     *
     * @param integer $traite
     *
     * @return Contact
     */
    public function setTraite($traite)
    {
        $this->traite = $traite;

        return $this;
    }

    /**
     * Get traite
     *
     * @return integer
     */
    public function getTraite()
    {
        return $this->traite;
    }

    /**
     * Set dateTraite
     *
     * @param \DateTime $dateTraite
     *
     * @return Contact
     */
    public function setDateTraite($dateTraite)
    {
        $this->dateTraite = $dateTraite;

        return $this;
    }

    /**
     * Get dateTraite
     *
     * @return \DateTime
     */
    public function getDateTraite()
    {
        return $this->dateTraite;
    }

    /**
     * Set idTraite
     *
     * @param integer $idTraite
     *
     * @return Contact
     */
    public function setIdTraite($idTraite)
    {
        $this->idTraite = $idTraite;

        return $this;
    }

    /**
     * Get idTraite
     *
     * @return integer
     */
    public function getIdTraite()
    {
        return $this->idTraite;
    }
}
