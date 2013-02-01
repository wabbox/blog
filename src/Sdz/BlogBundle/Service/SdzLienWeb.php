<?php
// src/Sdz/BolgBundle/Service/SdzLienWeb.php

namespace Sdz\BlogBundle\Service;

/**
 *Permet de mettre les liens en texte
 *
 * @author wabbox
 */

class SdzLienWeb extends \Twig_Extension
{

	/**
	 * On cherche dans le texte toutes les chaines de caractères
	 * commencant par http ou www et finissant  pas .+ 3 caratères.
	 *
	 * @param string $text
	 */

	public function getName()
	{
		return 'SdzLienWeb';
	}

	public function getFunctions()
	{
		return array(
		    'lienweb_check' => new \Twig_Function_Method($this, 'convertLien')
		);

	}

	function escape_some_special_chars($text)
	{
		// ^ $ | ( ) [ ]
		// * + { } , .
		//you can add more...except / because you mess up the link
		$patterns = array('/\^/', '/\|/',
		'/\(/', '/\)/', '/\[/', '/\]/', '/\*/', '/\+/', '/\{/', '/\}/', '/\,/', '/\./');
		$replace = '';

		return preg_replace($patterns,$replace, $text);
	}

	public function convertLien($text){
	/*	preg_replace('/((www|http://)[^ ]+)/', '<a href="\1">\1</a>', $text);
		preg_match_all(
            '#(http|https|ftp)://([A-Z0-9][A-Z0-9_-]*(?:.[A-Z0-9][A-Z0-9_-]*)+):?(d+)?/?#i',
            '/(\w+) (\d+), (\d+)/i'
            $text,
            $matches);*/
		$patterns = array('/\^/', '/\|/',
		'/\(/', '/\)/', '/\[/', '/\]/', '/\*/', '/\+/', '/\{/', '/\}/', '/\,/', '/\./');
		$replace = '';

		$text = preg_replace($patterns,$replace, $text);
		//$text = escape_some_special_chars($text);
		//preg_replace("/http:\/\/(.*?)\/? /",, $text);
		$text = preg_replace("/http:\/\/(.*?)\/? /", '<a href="\1">\1</a>', $text);


		return $text;
	}

}



?>