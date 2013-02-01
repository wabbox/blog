<?php
// src/Sdz/BlogBundle/Controller/BlogController.php

namespace Sdz\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
Symfony\Component\Httpfoundation\Response,
Sdz\BlogBundle\Entity\Article,
Sdz\BlogBundle\Entity\Image,
Sdz\BlogBundle\Entity\Mail,
Sdz\BlogBundle\Entity\Commentaire,
Sdz\BlogBundle\Form\ArticleType,
JMS\SecurityExtraBundle\Annotation\Secure;

class BlogController extends Controller
{
	public function testAction()
	{
		$article = new Article;

		$article->setDate(new \Datetime());  // Champ « date » O.K.
		$article->setTitre('abckkkkkkkk');           // Champ « titre » incorrect : moins de 10 caractères.
		$article->setContenu('blabla');    // Champ « contenu » incorrect : on ne le définit pas.
		$article->setAuteur('Ah');            // Champ « auteur » incorrect : moins de 2 caractères.

		// On récupère le service validator.
		$validator = $this->get('validator');

		// On déclenche la validation.
		$liste_erreurs = $validator->validate($article);

		// Si le tableau n'est pas vide, on affiche les erreurs.
		if(count($liste_erreurs) > 0)
		{
			return new Response(print_r($liste_erreurs, true));
		}
		else
		{
			return new Response("L'article est valide !");
		}
	}

	public function indexAction($page)
	{
		// On ne sait pas combien de pages il y a, mais on sait qu'une page
		// doit être supérieure ou égale à 1.
		if( $page < 1 )
		{
			// On déclenche une exception NotFoundHttpException, cela va afficher
			// la page d'erreur 404 (on pourra personnaliser cette page plus tard, d'ailleurs).
			throw $this->createNotFoundException('Page inexistante (page = '.$page.')');
		}
		// On récupère l'EntityManager
		$repository = $this->getDoctrine()
		->getManager()
		->getRepository('SdzBlogBundle:Article');
		$liste_articles = $repository->findAll();

		// Mais pour l'instant, on ne fait qu'appeler le template.
		return $this->render('SdzBlogBundle:Blog:index.html.twig', array(
			'liste_articles' => $liste_articles
			));
	}


	public function voirAction($id)
	{
		// On récupère l'EntityManager
		$em = $this->getDoctrine()
		->getEntityManager();

		// On récupère l'entité correspondant à l'id $id
		$article = $em->getRepository('SdzBlogBundle:Article')
		->find($id);

		if($article === null)
		{
			throw $this->createNotFoundException('Article[id='.$id.'] inexistant.');
		}

		// On récupère la liste des commentaires
		$liste_commentaires = $em->getRepository('SdzBlogBundle:Commentaire')
		->findAll();

		// Puis modifiez la ligne du render comme ceci, pour prendre en compte l'article :
		return $this->render('SdzBlogBundle:Blog:voir.html.twig', array(
			'article'		 => $article,
			'liste_commentaires' => $liste_commentaires
			));
	}

	/**
	 * @Secure(roles="ROLE_AUTEUR")
	 */
	public function ajouterAction()
	{
		$article = new Article;
		$form = $this->createForm(new ArticleType, $article);

		// On récupère la requête.
		$request = $this->get('request');

		// On vérifie qu'elle est de type « POST ».
		if( $request->getMethod() == 'POST' )
		{
			// On fait le lien Requête <-> Formulaire.
			$form->bind($request);

			// On vérifie que les valeurs rentrées sont correctes.
			// (Nous verrons la validation des objets en détail plus bas dans ce chapitre.)
			if( $form->isValid() )
			{
				// On l'enregistre notre objet $article dans la base de données.
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($article);
				$em->flush();

				// On redirige vers la page de visualisation de l'article nouvellement créé
				return $this->redirect($this->generateUrl('sdzblog_voir', array('id' => $article->getId())));
			}
		}

		// À ce stade :
		// - soit la requête est de type « GET », donc le visiteur vient d'arriver sur la page et veut voir le formulaire ;
		// - soit la requête est de type « POST », mais le formulaire n'est pas valide, donc on l'affiche de nouveau.

		return $this->render('SdzBlogBundle:Blog:ajouter.html.twig', array(
			'form' => $form->createView(),
			));
	}


	public function ex_ajouterAction()
	{
		// Création de l'entité
		$article = new Article();
		$article->setTitre('Mon dernier weekend');
		$article->setAuteur('Bibi');
		$article->setContenu("C'était vraiment super et on s'est bien amusé.");
		// On peut ne pas définir pas la date ni la publication,
		// car ces attributs sont définis automatiquement dans le constructeur

		// Création d'un premier commentaire
		$commentaire1 = new Commentaire();
		$commentaire1->setAuteur('winzou');
		$commentaire1->setContenu('On veut les photos !');

		// Création d'un deuxième commentaire, par exemple
		$commentaire2 = new Commentaire();
		$commentaire2->setAuteur('Choupy');
		$commentaire2->setContenu('Les photos arrivent !');

		// On lie les commentaires à l'article
		$article->addCommentaire($commentaire1);
		$article->addCommentaire($commentaire2);

		// Création de l'entité Image
		$image = new Image();
		$image->setUrl('http://uploads.siteduzero.com/icones/478001_479000/478657.png');
		$image->setAlt('Logo Symfony2');

		// On lie l'image à l'article
		$article->setImage($image);

		// On récupére l'EntityManager
		$em = $this->getDoctrine()->getManager();

		// Etape 1 : On "persiste" l'entité
		$em->persist($article);
		// Pour cette relation pas de cascade, car elle est définit dans l'entité Commentaire et non Article
		// On doit donc tout persister à la main ici
		//$em->persist($commentaire1);
		//$em->persist($commentaire2);

		// Etape 2 : On "flush" tout ce qui a été persisté avant
		$em->flush();

		// Reste de la méthode qu'on avait déjà écrit
		if( $this->get('request')->getMethod() == 'POST' )
		{
			$this->get('session')->setFlash('notice', 'Article bien enregistré');
			return $this->redirect( $this->generateUrl('sdzblog_voir', array('id' => $article->getId())) );
		}

		return $this->render('SdzBlogBundle:Blog:ajouter.html.twig');
	}
	/**
	 * @Secure(roles="ROLE_AUTEUR")
	 */
	public function modifierAction($id)
	{
		// Récupération d'un article déjà existant, d'id $id.
		$article = $this->getDoctrine()
		->getRepository('Sdz\BlogBundle\Entity\Article')
		->find($id);


		$form = $this->createForm(new ArticleType, $article);

		// On récupère la requête.
		$request = $this->get('request');

		// On vérifie qu'elle est de type « POST ».
		if( $request->getMethod() == 'POST' )
		{
			// On fait le lien Requête <-> Formulaire.
			$form->bind($request);

			// On vérifie que les valeurs rentrées sont correctes.
			// (Nous verrons la validation des objets en détail plus bas dans ce chapitre.)
			if( $form->isValid() )
			{
				// On l'enregistre notre objet $article dans la base de données.
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($article);
				$em->flush();

				// On redirige vers la page de visualisation de l'article nouvellement créé
				return $this->redirect($this->generateUrl('sdzblog_voir', array('id' => $article->getId())));
			}
		}

		// À ce stade :
		// - soit la requête est de type « GET », donc le visiteur vient d'arriver sur la page et veut voir le formulaire ;
		// - soit la requête est de type « POST », mais le formulaire n'est pas valide, donc on l'affiche de nouveau.

		return $this->render('SdzBlogBundle:Blog:modifier.html.twig', array('article' => $article,
			'form' => $form->createView()));
	}

	public function supprimerAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$article = $em->getRepository('SdzBlogBundle:Article')->find($id);
		$em->remove($article);
		$em->flush();


		return $this->redirect($this->generateUrl('sdzblog_accueil'));
	}

	public function menuAction($nombre) // Ici, nouvel argument $nombre, on a transmis via le "with" depuis la vue
	{
		$repository = $this->getDoctrine()
		->getManager()
		->getRepository('SdzBlogBundle:Article');

		$liste_articles = $repository->findCinqDerniers(5);

		return $this->render('SdzBlogBundle:Blog:menu.html.twig', array(
		    'liste_articles' => $liste_articles // C'est ici tout l'intérêt : le contrôleur passe les variables nécessaires au template !
		    ));
	}

	public function mailAction()
	{
		$mail = new Mail;
		$formBuilder = $this->createFormBuilder($mail);
		$formBuilder
		->add('nomDest',        'text', array('label' => 'Nom du destinataire'))
		->add('prenomDest',       'text', array('label' => 'Prénom du destinataire'))
		->add('emailDest',     'text', array('label' => 'Email du destinataire'))
		->add('nomExpe',        'text', array('label' => 'Nom de l\'expéditeur'))
		->add('prenomExpe',       'text', array('label' => 'Prénom de l\'expéditeur'))
		->add('emailExpe',     'text', array('label' => 'Email de l\'expéditeur'))
		->add('objet',      'text', array('label' => 'Objet du mail'))
		->add('contenu',     'ckeditor', array('label' => 'Contenu du mail'));
		$form = $formBuilder->getForm();
		// On récupère la requête.
		$request = $this->get('request');
		// On vérifie qu'elle est de type « POST ».
		if( $request->getMethod() == 'POST' )
		{

			// On fait le lien Requête <-> Formulaire.
			$form->bind($request);

			// On vérifie que les valeurs rentrées sont correctes.
			// (Nous verrons la validation des objets en détail plus bas dans ce chapitre.)
			if( $form->isValid() )
			{
				// On envoie le mail
				$mailer = $this->get('mailer');
				$message = \Swift_Message::newInstance()
				->setSubject($mail->getObjet())
				->setFrom($mail->getEmailDest())
				->setTo($mail->getEmailExpe())
				->setBody($mail->getContenu());
				$type = $message->getHeaders()->get('Content-Type');
				$type->setValue('text/html');
				$type->setParameter('charset', 'utf-8');

				$mailer->send($message);

				// On redirige vers la page de visualisation de l'article nouvellement créé
				return $this->redirect($this->generateUrl('sdzblog_accueil'));
			}
		}

		return $this->render('SdzBlogBundle:Blog:mail.html.twig', array(
			'form' => $form->createView()));
	}
}

