<?php
// src/Sdz/BlogBundle/Form/ArticleType.php

namespace JuniorEsiee\PageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CommonFormType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('societe',        'text',     array('required' => false, 'label'=>"Société",  'attr' => array('placeholder' => 'Renseignez ici le nom de votre entreprise, agence, association...')))
      ->add('nom',            'text',     array('attr' => array('placeholder' => 'Renseignez ici votre nom')))
      ->add('prenom',         'text',     array('label'=>"Prénom", 'attr' => array('placeholder' => 'Renseignez ici votre prénom')))
      ->add('telephone',      'number',   array('label'=>"Téléphone", 'attr' => array('placeholder' => 'Renseignez ici votre numéro de téléphone') ))
	  ->add('email',          'email',    array('label'=>"E-mail", 'attr' => array('placeholder' => 'Renseignez ici votre adresse e-mail')))
	  ->add('pays', 		  'country',  array('required' => false, 'data' => 'FR'))   
    ;
  }

  public function getName()
  {
    return 'junioresiee_pagebundle_commonform';
  }
}