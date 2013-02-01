<?php
// src/Sdz/BlogBundle/Validator/AntiFloodValidator.php

namespace Sdz\BlogBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AntiFloodValidator extends ConstraintValidator
{
	public function validate($value, Constraint $constraint)
	{
		// Pour l'instant, on consid�re comme flood tout message de moins de 3 caract�res
		if (strlen($value) < 3)
		{
			// C'est cette ligne qui d�clenche l'erreur pour le formulaire, avec en argument le message
			$this->context->addViolation($constraint->message);
		}
	}
}
