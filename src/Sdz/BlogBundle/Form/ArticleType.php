<?php

namespace Sdz\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        	->add('date',        'date')
        	->add('titre',       'text')
        	->add('contenu',     'textarea', array('required' => false))
        	->add('auteur',      'text')
        	->add('publication', 'checkbox', array('required' => false))
        	->add('image',       new ImageType())
            ->add('categories', 'entity', array(
                'class' => 'Sdz\BlogBundle\Entity\Client',
                'property' => 'nom',
                'empty_value' => 'Choisissez une catégorie'
            ) )
        	->add('categories',  'collection', array('type'         => new CategorieType(),
        	'allow_add'    => true,
        	'allow_delete' => true,
        	'by_reference' => false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sdz\BlogBundle\Entity\Article'
        ));
    }

    public function getName()
    {
        return 'sdz_blogbundle_articletype';
    }
}
