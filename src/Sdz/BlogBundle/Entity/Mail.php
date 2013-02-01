<?php

namespace Sdz\BlogBundle\Entity;

class Mail
{
	private $nomDest;
	private $prenomDest;
	private $emailDest;
	private $nomExpe;
	private $prenomExpe;
	private $emailExpe;
	private $objet;
	private $contenu;

	public function getNomDest()
	{
		return $this->nomDest;
	}

	public function setNomDest($nomDest)
	{
		$this->nomDest = $nomDest;

		return $this;
	}

	public function getPrenomDest()
	{
		return $this->prenomDest;
	}

	public function setPrenomDest($prenomDest)
	{
		$this->prenomDest = $prenomDest;

		return $this;
	}

	public function getEmailDest()
	{
		return $this->emailDest;
	}

	public function setEmailDest($emailDest)
	{
		$this->emailDest = $emailDest;

		return $this;
	}

	public function getNomExpe()
	{
		return $this->nomExpe;
	}

	public function setNomExpe($nomExpe)
	{
		$this->nomExpe = $nomExpe;

		return $this;
	}

	public function getPrenomExpe()
	{
		return $this->prenomExpe;
	}

	public function setPrenomExpe($prenomExpe)
	{
		$this->prenomExpe = $prenomExpe;

		return $this;
	}

	public function getEmailExpe()
	{
		return $this->emailExpe;
	}

	public function setEmailExpe($emailExpe)
	{
		$this->emailExpe = $emailExpe;

		return $this;
	}

	public function getObjet()
	{
		return $this->objet;
	}

	public function setObjet($objet)
	{
		$this->objet = $objet;

		return $this;
	}

	public function getContenu()
	{
		return $this->contenu;
	}

	public function setContenu($contenu)
	{
		$this->contenu = $contenu;

		return $this;
	}
}