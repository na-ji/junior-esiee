<?php

namespace JuniorEsiee\FinancesBundle\Form;

use JuniorEsiee\FinancesBundle\Form\EventListener\AddPaidAtSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use JMS\DiExtraBundle\Annotation\FormType;

/**
 * @FormType
 */
class CustomerInvoiceType extends AbstractType
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
            ->add('fc', 'text', array(
                'label' => 'FC',
            ))
            ->add('issuedAt', 'datepicker', array(
                'label' => "Date d'émmission",
            ))
            ->add('dueDate', 'datepicker', array(
                'label' => "Date d'échéance",
            ))
            ->add('client', 'text', array(
                'label' => 'Client',
            ))
            ->add('percentage', 'text', array(
                'label' => 'Pourcentage',
            ))
            ->add('amount', 'text', array(
                'label' => 'Montant HT',
            ))
            ->add('taxes', 'text', array(
                'label' => 'TVA',
            ))
            ->add('file', 'sonata_media_type', array(
                'required'      => false,
                'provider'      => 'sonata.media.provider.file',
                'context'       => 'invoice',
                'new_on_update' => false,
                'label'         => 'Fichier',
            ))
        ;

        $builder->addEventSubscriber(new AddPaidAtSubscriber);
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JuniorEsiee\FinancesBundle\Entity\CustomerInvoice'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'je_financesbundle_customerinvoice';
    }
}
