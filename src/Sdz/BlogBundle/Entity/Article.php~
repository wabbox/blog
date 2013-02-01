<?php

namespace Sdz\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Sdz\BlogBundle\Entity\Article
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Sdz\BlogBundle\Entity\ArticleRepository")
 *
 * @UniqueEntity(fields="titre", message="Un article existe déjà avec ce titre.")
 */
class Article
{
	public function __construct()
    {
		// Rappelez-vous, on a un attribut qui doit contenir un ArrayCollection, on doit l'initialiser dans le constructeur
		$this->commentaires = new \Doctrine\Common\Collections\ArrayCollection();
		$this->categories = new \Doctrine\Common\Collections\ArrayCollection();
        $this->date = new \Datetime(); // Par défaut, la date de l'article est la date d'aujourd'hui
		$this->publication = true;
    }
	/**
	 * @ORM\OneToMany(targetEntity="Sdz\BlogBundle\Entity\Commentaire", mappedBy="article")
	 */
	private $commentaires; // Ici commentaires prend un "s", car un article a plusieurs commentaires !
	/**
	 * @ORM\ManyToMany(targetEntity="Sdz\BlogBundle\Entity\Categorie", cascade={"persist"})
	 */
	private $categories;
	/**
	 * @ORM\OneToOne(targetEntity="Sdz\BlogBundle\Entity\Image", cascade={"persist"})
	 * @ORM\JoinColumn(nullable=false)
	 * @Assert\Valid()
	 */
	private $image;

	/**
	 * @ORM\Column(name="publication", type="boolean")
	 */
	private $publication;

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime $date
     *
     * @ORM\Column(name="date", type="datetime")
     * @Assert\DateTime(message="La date doit être valide.")
     */
    private $date;

    /**
     * @var string $titre
     *
     * @ORM\Column(name="titre", type="string", length=255, unique=true)
     * @Assert\MinLength(limit=5, message="Le titre doit au moins contenir {{ limit }} caractères.")
     */
    private $titre;

    /**
     * @var string $auteur
     *
     * @ORM\Column(name="auteur", type="string", length=255)
     * @Assert\MinLength(limit=2, message="Le nom de l'auteur doit au moins contennir {{ limit }} caractères.")
     */
    private $auteur;

    /**
     * @var string $contenu
     *
     * @ORM\Column(name="contenu", type="text")
     * @Assert\NotBlank(message="Le contenue ne peut être vide.")
     */
    private $contenu;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Article
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set titre
     *
     * @param string $titre
     * @return Article
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
     * Set auteur
     *
     * @param string $auteur
     * @return Article
     */
    public function setAuteur($auteur)
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * Get auteur
     *
     * @return string
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * Set contenu
     *
     * @param string $contenu
     * @return Article
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set publication
     *
     * @param boolean $publication
     * @return Article
     */
    public function setPublication($publication)
    {
        $this->publication = $publication;

        return $this;
    }

    /**
     * Get publication
     *
     * @return boolean
     */
    public function getPublication()
    {
        return $this->publication;
    }

    /**
     * Set image
     *
     * @param Sdz\BlogBundle\Entity\Image $image
     * @return Article
     */
    public function setImage(\Sdz\BlogBundle\Entity\Image $image = NULL)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return Sdz\BlogBundle\Entity\Image
     */
    public function getImage()
    {
        return $this->image;
    }

	/**
	 * Set categories
	 *
	 * @param Sdz\BlogBundle\Entity\Categorie $categories
	 * @return Article
	 */
	public function setCategories($categories)
	{
		$this->categories = $categories;
		return $this;
	}

	/**
	 * Add categories
	 *
	 * @param Sdz\BlogBundle\Entity\Categorie $categories
	 */
	public function addCategories(\Sdz\BlogBundle\Entity\Categorie $categories) // addCategorie sans « s » !
	{
		// Ici, on utilise l'ArrayCollection vraiment comme un tableau, avec la syntaxe []
		$this->categories[] = $categories;
		return $this;
	}

	/**
	 * Remove categories
	 *
	 * @param Sdz\BlogBundle\Entity\Categorie $categories
	 */
	public function removeCategories(\Sdz\BlogBundle\Entity\Categorie $categories) // removeCategorie sans « s » !
	{
		// Ici on utilise une méthode de l'ArrayCollection, pour supprimer la catégorie en argument
		$this->categories->removeElement($categories);
	}

	/**
	 * Get categories
	 *
	 * @return Doctrine\Common\Collections\Collection
	 */
	public function getCategories() // Notez le « s », on récupère une liste de catégories ici !
	{
		return $this->categories;
	}

	public function addCommentaire(\Sdz\BlogBundle\Entity\Commentaire $commentaire)
	{
		$this->commentaires[] = $commentaire;
		$commentaire->setArticle($this);
		return $this;
	}

	public function removeCommentaire(\Sdz\BlogBundle\Entity\Commentaire $commentaire)
	{
		$this->commentaires->removeElement($commentaire);
	}

	public function getCommentaires()
	{
		return $this->commentaires;
	}

    /**
     * Add categories
     *
     * @param Sdz\BlogBundle\Entity\Categorie $categories
     * @return Article
     */
    public function addCategorie(\Sdz\BlogBundle\Entity\Categorie $categories)
    {
        $this->categories[] = $categories;
    
        return $this;
    }

    /**
     * Remove categories
     *
     * @param Sdz\BlogBundle\Entity\Categorie $categories
     */
    public function removeCategorie(\Sdz\BlogBundle\Entity\Categorie $categories)
    {
        $this->categories->removeElement($categories);
    }
}