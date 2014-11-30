<?php

namespace JuniorEsiee\PageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('common', new CommonFormType())
            ->add('message', 'textarea', array(
                'attr'        => array(
                    'placeholder' => 'Renseignez ici votre proposition, demande, question, remarque...', 
                    'rows'        => '10'
                ), 
                'constraints' => new NotBlank(),
            ))
        ;
    }

    public function getName()
    {
        return 'junioresiee_pagebundle_contactform';
    }
}
