<?php

namespace Application\Sonata\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use JMS\DiExtraBundle\Annotation\FormType;

/**
 * @FormType
 */
class UserType extends AbstractType
{
    private $owner;

    public function __construct($owner = false)
    {
        $this->owner = $owner;
    }


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'text', array(
                'label' => "Nom d'utilisateur"
            ))
            ->add('email', 'email', array(
                'label' => 'Email'
            ))
            ->add('lastname', null, array(
                'required' => false,
                'label'    => 'Nom de famille',
            ))
            ->add('firstname', null, array(
                'required' => false,
                'label'    => 'Prénom',
            ))
            ->add('gender', 'sonata_user_gender', array(
                'required'           => false,
                'translation_domain' => 'ApplicationSonataUserBundle',
                'expanded'           => true,
                'empty_value'        => false,
                'label'              => 'Sexe',
            ))
            ->add('promo', 'choice', array(
                'choices'  => array(
                    'E1' => 'E1', 
                    'E2' => 'E2', 
                    'E3' => 'E3', 
                    'E4' => 'E4', 
                    'E5' => 'E5', 
                ),
                'expanded'    => true,
                'required'    => false,
                'empty_value' => false
            ))
            ->add('phone', null, array(
                'required' => false,
                'label'    => 'Téléphone',
            ))
            ->add('address', null, array(
                'required' => false,
                'label'    => 'Adresse',
            ))
            ->add('zipCode', null, array(
                'required' => false,
                'label'    => 'Code Postal',
            ))
            ->add('city', null, array(
                'required' => false,
                'label'    => 'Ville',
            ))
            ->add('birthCity', null, array(
                'required' => false,
                'label'    => 'Commune de naissance',
            ))
            ->add('birthDepartment', null, array(
                'required' => false,
                'label'    => 'Département de naissance',
            ))
            ->add('nationality', null, array(
                'required' => false,
                'label'    => 'Nationalité',
            ))
            ->add('socialSecurityNumber', null, array(
                'required' => false,
                'label'    => 'Numéro de sécurité sociale',
            ))
            ->add('socialSecurityCenter', 'choice', array(
                'choices'     => array(
                    'LMDE'   => 'LMDE', 
                    'SMEREP' => 'SMEREP', 
                    'Autre'  => 'Autre'
                ),
                'expanded'    => true,
                'required'    => false,
                'empty_value' => false,
                'label'       => 'Centre de sécurité sociale',
            ))
            ->add('cv', 'sonata_media_type', array(
                'required'      => false,
                'provider'      => 'sonata.media.provider.file',
                'context'       => 'attachment',
                'new_on_update' => false,
                'label'         => 'CV',
            ))
            ->add('socialSecurity', 'sonata_media_type', array(
                'required'      => false,
                'provider'      => 'sonata.media.provider.file',
                'context'       => 'attachment',
                'new_on_update' => false,
                'label'         => 'Attestation de sécurité sociale',
            ))
            ->add('chequeNumber', null, array(
                'required' => false,
                'label'    => 'Numéro de chèque',
            ))
            ->add('skillCategories', null, array(
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'label'    => 'Domaine(s) de compétence'
            ))
            ->add('skills', null, array(
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'label'    => 'Compétence(s)'
            ))
        ;

        if (!$this->owner)
        {
            $builder
                ->add('isComplete', 'sonata_type_boolean', array(
                    'expanded' => true,
                    'label'    => 'Dossier complet'
                ))
                ->add('registredAt', null, array(
                    'label'    => "Date d'inscription",
                    'required' => false
                ))
                ->add('group', 'entity', array(
                    'label'    => 'Poste',
                    'class'    => 'ApplicationSonataUserBundle:Group',
                    'multiple' => false,
                ))
            ;
        }
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\Sonata\UserBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'je_userbundle_user';
    }
}
