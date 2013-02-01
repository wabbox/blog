<?php
// src/Sdz/BlogBundle/Command/FixtureCategoriesCommand.php

namespace Sdz\BlogBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Sdz\BlogBundle\Entity\Categorie;

class FixtureCategoriesCommand extends ContainerAwareCommand
{
	protected function configure()
	{
		$this->setName('sdzblog:fixture:categories');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		// On récupère l'EntityManager
		$em = $this->getContainer()->get('doctrine.orm.entity_manager');

		// Liste des noms de catégorie à ajouter
		$noms = array('Symfony2', 'Doctrine2', 'Tutoriel', 'Evenement');

		foreach($noms as $i => $nom)
		{
			$output->writeln('Creation de la categorie : '.$nom);

			// On crée la catégorie
			$liste_categories[$i] = new Categorie();
			$liste_categories[$i]->setNom($nom);

			// On la persiste
			$em->persist($liste_categories[$i]);
		}

		$output->writeln('Enregistrement des categories...');

		// On déclenche l'neregistrement
		$em->flush();

		// On retourne 0 pour dire que la commande s'est bien exécutée
		return 0;
	}
}
