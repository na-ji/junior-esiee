<?php
// src/Sdz/BlogBundle/Form/ArticleType.php

namespace JuniorEsiee\PageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OffreFormType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
	  ->add('common',        new CommonFormType(), array('label' => false))
      ->add('adresse',        'text', array( 'attr' => array('placeholder' => 'Entrez ici le nom et le numéro de la rue de votre adresse')))
      ->add('codepostal',     'number', array('label' => "Code postal", 'attr' => array('placeholder' => 'Entrez ici le code postal / CEDEX de votre adresse')))
      ->add('ville',          'text', array('attr' => array('placeholder' => 'Entrez ici votre ville')))
      ->add('description',    'textarea', array( 'attr' => array('placeholder' => 'Entrez ici les éléments clés de votre demandes (objectifs, contexte, compétences, durée de réalisation, budget alloué...)', 'rows' => '15')))
	  ->add('cahiercharges',  'file',  array( 'label'=>"Cahier des charges", 'required' => false))
	  ->add('chartegraph',    'file',  array( 'label'=>"Charte graphique", 'required' => false))   
    ;
  }

  public function getName()
  {
    return 'junioresiee_pagebundle_offreform';
  }
}