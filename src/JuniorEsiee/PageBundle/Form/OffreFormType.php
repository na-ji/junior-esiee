<?php

namespace JuniorEsiee\PageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints as Assert;

class OffreFormType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
		->add('common', new CommonFormType(), array(
			'label' => false
		))
		->add('adresse', 'text', array(
			'attr'        => array(
				'placeholder' => 'Entrez ici le nom et le numéro de la rue de votre adresse'
			), 
			'constraints' => new NotBlank(),
		))
		->add('codepostal', 'integer', array(
			'label'       => "Code postal", 	
			'attr'        => array(
				'placeholder' => 'Entrez ici le code postal / CEDEX de votre adresse'
			), 
			'constraints' => array(
				new NotBlank(),
				new Assert\Type(array(
					'type' => 'integer',
				)),
				new Assert\Range(array(
					'min' => 0,
					'max' => 1000000
				))
			)
		))
		->add('ville', 'text', array(
			'attr'        => array(
				'placeholder' => 'Entrez ici votre ville'
			), 
			'constraints' => new NotBlank(),
		))
		->add('description', 'textarea', array(
			'attr'        => array(
				'placeholder' => 'Entrez ici les éléments clés de votre demandes (objectifs, contexte, compétences, durée de réalisation, budget alloué...)',
				'rows'        => '15'
			), 
			'constraints' => new NotBlank(),
		))
		->add('cahiercharges', 'file', array(
			'label'       => 'Cahier des charges', 
			'required'    => false,
			'constraints' => new File(array(
            	'maxSize' => '10000k',
			))
		))
		->add('chartegraph', 'file', array(
			'label'       => 'Charte graphique', 
			'required'    => false, 
			'constraints' => new File(array(
            	'maxSize' => '10000k',
			))
		))   
		;
	}

	public function getName()
	{
		return 'junioresiee_pagebundle_offreform';
	}
}
