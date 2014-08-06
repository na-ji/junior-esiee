<?php
// src/Sdz/BlogBundle/Form/ArticleType.php

namespace JuniorEsiee\PageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CommonFormType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
		->add('societe',        'text',     array(
			'required' => false, 
			'label'=>"Société", 
			'attr' => array('placeholder' => 'Renseignez ici le nom de votre entreprise, agence, association...')
			))
		
		->add('nom',            'text',     array(
			'attr' => array('placeholder' => 'Renseignez ici votre nom'), 
			 'constraints' =>new NotBlank(),
			))
		->add('prenom',         'text',     array(
			'label'=>"Prénom",
			'attr' => array('placeholder' => 'Renseignez ici votre prénom'),
			'constraints' => new NotBlank(),
			))
		->add('telephone',      'text',   array(
			'label'=>"Téléphone", 
			'attr' => array('placeholder' => 'Renseignez ici votre numéro de téléphone'),
			 'constraints' => array(new Length(array('min' => 10)), 
				new NotBlank,
				)
			))
		 ->add('email',          'email',    array(
			 'label'=>"E-mail", 
			 'attr' => array('placeholder' => 'Renseignez ici votre adresse e-mail'),
			 'constraints' => new NotBlank(),
			 ))
		 ->add('pays', 		  'country',  array(
			'required' => false,
			'data' => 'FR'
			))   
		;
	}

	 public function getName()
	 {
		return 'junioresiee_pagebundle_commonform';
	 }
}