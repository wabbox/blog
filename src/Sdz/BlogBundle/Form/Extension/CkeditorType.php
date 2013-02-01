<?php
// src/Sdz/BlogBundle/Form/Extension/CkeditorType.php

/**
 * Type de champ de formulaire pour CKEditor.
 *
 * @author Leglopin
 */
namespace Sdz\BlogBundle\Form\Extension;

use Symfony\Component\Form\AbstractType;

class CkeditorType extends AbstractType
{
	public function getParent()
	{
		return 'textarea';
	}

	public function getName()
	{
		return 'ckeditor';
	}

	public function getDefaultOptions(array $options)
	{
		$defaultOptions = parent::getDefaultOptions($options);
		$defaultOptions['attr']['class'] = 'ckeditor';

		return $defaultOptions;
	}
}
