<?php

namespace JuniorEsiee\FinancesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use JMS\DiExtraBundle\Annotation\FormType;

/**
 * @FormType
 */
class SupplierInvoiceType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ref', 'text', array(
                'label' => 'Référence',
            ))
            ->add('createdAt', 'datepicker', array(
                'label' => 'Date',
            ))
            ->add('supplier', 'text', array(
                'label' => 'Récepteur',
            ))
            ->add('description', 'text', array(
                'label' => 'Description',
            ))
            ->add('totalAmount', 'text', array(
                'label' => 'Montant TTC',
            ))
            ->add('taxesHigh', 'text', array(
                'label' => 'Montant TVA 19.6 %',
            ))
            ->add('taxesMedium', 'text', array(
                'label' => 'Montant TVA 7.0 %',
            ))
            ->add('taxesLow', 'text', array(
                'label' => 'Montant TVA 5.5 %',
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JuniorEsiee\FinancesBundle\Entity\SupplierInvoice'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'je_financesbundle_supplierinvoice';
    }
}
