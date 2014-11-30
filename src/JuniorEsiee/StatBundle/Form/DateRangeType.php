<?php

namespace JuniorEsiee\StatBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DateRangeType extends AbstractType
{
    private $months;
    private $years;

    function __construct(array $months, array $years)
    {
        $this->months = $months;
        $this->years = array_combine($years, $years);
    }


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('monthFrom', 'choice', array(
                'choices' => $this->months,
                'attr' => array(
                    'class' => 'inline'
                )
            ))
            ->add('yearFrom', 'choice', array(
                'choices' => $this->years,
                'attr' => array(
                    'class' => 'inline'
                )
            ))
            ->add('monthTo', 'choice', array(
                'choices' => $this->months,
                'attr' => array(
                    'class' => 'inline'
                )
            ))
            ->add('yearTo', 'choice', array(
                'choices' => $this->years,
                'attr' => array(
                    'class' => 'inline'
                )
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JuniorEsiee\StatBundle\Entity\DateRange'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'je_statbundle_daterange';
    }
}
