<?php

namespace JuniorEsiee\FinancesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use JMS\DiExtraBundle\Annotation\FormType;

/**
 * @FormType
 */
class PaymentSlipType extends AbstractType
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
            ->add('bv', 'text', array(
                'label' => 'BV',
            ))
            ->add('createdAt', 'datepicker', array(
                'label' => "Date",
            ))
            ->add('client', 'text', array(
                'label' => 'Client',
            ))
            ->add('student', 'text', array(
                'label' => 'Réalisateur',
            ))
            ->add('amount', 'text', array(
                'label' => 'Montant HT',
            ))
            ->add('numberOfDays', 'text', array(
                'label' => 'Nombre de JEH',
            ))
            ->add('smic', 'text', array(
                'label' => 'SMIC horraire',
            ))
            ->add('urssaf_1_1', 'text', array(
                'label' => 'Cotisations base brute',
            ))
            ->add('urssaf_1_2', 'text', array(
                'label' => 'Cotisations base brute',
            ))
            ->add('urssaf_2_1', 'text', array(
                'label' => 'Cotisations base URSSAF',
            ))
            ->add('urssaf_2_2', 'text', array(
                'label' => 'Cotisations base URSSAF',
            ))
            ->add('urssaf_2_3', 'text', array(
                'label' => 'Cotisations base URSSAF',
            ))
            ->add('urssaf_2_4', 'text', array(
                'label' => 'Cotisations base URSSAF',
            ))
            ->add('file', 'sonata_media_type', array(
                'required'      => false,
                'provider'      => 'sonata.media.provider.file',
                'context'       => 'slip',
                'new_on_update' => false,
                'label'         => 'Fichier',
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JuniorEsiee\FinancesBundle\Entity\PaymentSlip'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'je_financesbundle_paymentslip';
    }
}
