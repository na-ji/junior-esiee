<?php

namespace JuniorEsiee\BusinessBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * @DI\FormType
 */
class AssociationListType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'association_list';
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'entity';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array('class', 'properties'));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'multiple'   => $options['multiple'],
            'properties' => $options['properties'],
        ));
    }
}