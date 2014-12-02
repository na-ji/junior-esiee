<?php

namespace JuniorEsiee\BusinessBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use JMS\DiExtraBundle\Annotation as DI;
use JuniorEsiee\BusinessBundle\Entity\Project;

/**
 * @DI\FormType
 */
class ProjectType extends AbstractType
{
    /**
     * @DI\Inject("security.context")
     */
    public $securityContext;

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('clientLastName', null, array(
                'label' => 'Nom de famille'
            ))
            ->add('clientFirstName', null, array(
                'label' => 'Prénom'
            ))
            ->add('clientCompany', null, array(
                'required' => false,
                'label'    => 'Entreprise'
            ))
            ->add('clientPhone', null, array(
                'label' => 'Téléphone'
            ))
            ->add('clientEmail', null, array(
                'label' => 'Email'
            ))
            ->add('clientAddress', null, array(
                'label' => 'Adresse'
            ))
            ->add('clientZipCode', null, array(
                'label' => 'Code Postal'
            ))
            ->add('clientCity', null, array(
                'label' => 'Ville'
            ))
            ->add('depositDate', 'date', array(
                'required' => false,
                'label'    => 'Date de dépôt'
            ))
            ->add('title', null, array(
                'required' => false,
                'label'    => 'Titre'
            ))
            ->add('description', null, array(
                'required' => false,
                'label'    => 'Description'
            ))
            ->add('delay', 'date', array(
                'required' => false,
                'label'    => 'Délais'
            ))
        ;

        if ($this->securityContext->isGranted('ROLE_ADMIN'))
        {
            $builder
                ->add('commercial', 'association_list', array(
                    'required'   => false,
                    'class'      => 'ApplicationSonataUserBundle:User',
                    'properties' => array('id', 'username'),
                    'role'       => 'ROLE_CHARGE'
                ))
                ->add('rbu', 'association_list', array(
                    'required'   => false,
                    'class'      => 'ApplicationSonataUserBundle:User',
                    'properties' => array('id', 'username'),
                    'role'       => 'ROLE_ADMIN'
                ))
                ->add('students', 'association_list', array(
                    'required'   => false,
                    'class'      => 'ApplicationSonataUserBundle:User',
                    'properties' => array('id', 'username'),
                    'multiple'   => true,
                ))
            ;
        }

        if ($this->securityContext->isGranted('ROLE_CHARGE'))
        {
            $builder
                ->add('skillCategories', null, array(
                    'required' => false,
                    'multiple' => true,
                    'expanded' => true,
                    'label'    => 'Majeur(s)'
                ))
                ->add('skills', null, array(
                    'required' => false,
                    'multiple' => true,
                    'expanded' => true,
                    'label'    => 'Compétence(s)'
                ))
                ->add('state', 'choice', array(
                    'required'           => false,
                    'expanded'           => true,
                    'multiple'           => false,
                    'empty_value'        => false,
                    'choices'            => array_combine(Project::getStates(), Project::getStates()),
                    'translation_domain' => 'JuniorEsieeBusinessBundle',
                    'label'              => 'Statut'
                ))
                ->add('scopeStatement', 'sonata_media_type', array(
                    'required'      => false,
                    'provider'      => 'sonata.media.provider.file',
                    'context'       => 'scopeStatement',
                    'new_on_update' => false,
                    'label'         => 'Cahier des charges',
                ))
                ->add('graphicCharter', 'sonata_media_type', array(
                    'required'      => false,
                    'provider'      => 'sonata.media.provider.file',
                    'context'       => 'graphicCharter',
                    'new_on_update' => false,
                    'label'         => 'Charte graphique',
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
            'data_class' => 'JuniorEsiee\BusinessBundle\Entity\Project'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'junioresiee_businessbundle_project';
    }
}
