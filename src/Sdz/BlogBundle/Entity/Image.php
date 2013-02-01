<?php

namespace Sdz\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Sdz\BlogBundle\Entity\Image
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Sdz\BlogBundle\Entity\ImageRepository")
 */
class Image
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $url
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var string $alt
     *
     * @ORM\Column(name="alt", type="string", length=255)
     */
    private $alt;

	/**
	 * @ORM\Column(type="string", length=255)
	 *
	 *  @Assert\NotBlank
	 */
	public $name;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	public $path;

	/**
	 * @Assert\File(maxSize="6000000")
	 */
	public $file;

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
     * Set url
     *
     * @param string $url
     * @return Image
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set alt
     *
     * @param string $alt
     * @return Image
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Image
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Image
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

	public function getAbsolutePath()
	{
		return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
	}

	public function getWebPath()
	{
		return null === $this->path ? null : $this->getUploadDir().'/'.$this->path;
	}

	protected function getUploadRootDir()
	{
		// le chemin absolu du r�pertoire o� les documents upload�s doivent �tre sauvegard�s
		return __DIR__.'/../../../../web/'.$this->getUploadDir();
	}

	protected function getUploadDir()
	{
		// on se d�barrasse de � __DIR__ � afin de ne pas avoir de probl�me lorsqu'on affiche
		// le document/image dans la vue.
		return 'uploads/documents';
	}

	/**
	 * @ORM\PrePersist()
	 * @ORM\PreUpdate()
	 */
	public function preUpload()
	{
		if (null !== $this->file) {
			// faites ce que vous voulez pour g�n�rer un nom unique
			$this->path = sha1(uniqid(mt_rand(), true)).'.'.$this->file->guessExtension();
		}
	}

	/**
	 * @ORM\PostPersist()
	 * @ORM\PostUpdate()
	 */
	public function upload()
	{
		if (null === $this->file) {
			return;
		}

		// s'il y a une erreur lors du d�placement du fichier, une exception
		// va automatiquement �tre lanc�e par la m�thode move(). Cela va emp�cher
		// proprement l'entit� d'�tre persist�e dans la base de donn�es si
		// erreur il y a
		$this->file->move($this->getUploadRootDir(), $this->path);

		unset($this->file);
	}

	/**
	 * @ORM\PostRemove()
	 */
	public function removeUpload()
	{
		if ($file = $this->getAbsolutePath()) {
			unlink($file);
		}
	}
}