<?php
// src/Sdz/BlogBundle/Form/ArticleType.php

namespace JuniorEsiee\PageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContactFormType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
	  ->add('common',        new CommonFormType())
      ->add('message',        'textarea', array( 'attr' => array('placeholder' => 'Renseignez ici votre proposition, demande, question, remarque...', 'rows' => '10')))
    ;
  }

  public function getName()
  {
    return 'junioresiee_pagebundle_contactform';
  }
}