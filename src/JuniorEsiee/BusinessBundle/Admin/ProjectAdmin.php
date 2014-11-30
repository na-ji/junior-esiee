<?php

namespace JuniorEsiee\BusinessBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class ProjectAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('clientLastName')
            ->add('clientFirstName')
            ->add('clientCompany')
            ->add('clientPhone')
            ->add('clientEmail')
            ->add('depositDate')
            ->add('title')
            ->add('description')
            ->add('delay')
            ->add('state')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('clientLastName')
            ->add('clientFirstName')
            ->add('clientCompany')
            ->add('depositDate')
            ->add('title')
            ->add('delay')
            ->add('state')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Client')
                ->add('clientLastName')
                ->add('clientFirstName')
                ->add('clientCompany')
                ->add('clientPhone')
                ->add('clientEmail')
            ->end()
            ->with('Projet')
                ->add('depositDate', 'date')
                ->add('title')
                ->add('description')
                ->add('delay', 'date')
                ->add('skillCategories', 'sonata_type_model', array(
                    'multiple' => true,
                ))
                ->add('skills', 'sonata_type_model', array(
                    'multiple' => true,
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
            ->end()
            ->with('Suivi')
                ->add('state')
                ->add('commercial', 'sonata_type_model_list', array(
                    'required'  => false,
                ))
                ->add('rbu', 'sonata_type_model_list', array(
                    'required'  => false,
                ))
            ->end()
            ->with('Réalisation')
                ->add('students', 'sonata_type_model', array(
                    'multiple' => true,
                    'required'  => false,
                ))
            ->end()
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('clientLastName')
            ->add('clientFirstName')
            ->add('clientCompany')
            ->add('clientPhone')
            ->add('clientEmail')
            ->add('depositDate')
            ->add('title')
            ->add('description')
            ->add('delay')
            ->add('skillCategories')
            ->add('skills')
            ->add('state')
            ->add('commercial')
            ->add('rbu')
            ->add('students')
        ;
    }
}
