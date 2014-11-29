<?php

namespace Application\Sonata\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        // add your custom field
        $builder
            ->add('firstName', 'text', array(
                'label' => 'Prénom :'
            ))
            ->add('lastName', 'text', array(
                'label' => 'Nom de famille :'
            ))
            ->add('phone', 'text', array(
                'label' => 'Téléphone :'
            ))
        ;
    }

    public function getName()
    {
        return 'je_user_registration';
    }
}