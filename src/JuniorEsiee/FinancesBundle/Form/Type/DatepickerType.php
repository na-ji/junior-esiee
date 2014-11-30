<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Nico
 * Date: 28/10/13
 * Time: 17:31
 * To change this template use File | Settings | File Templates.
 */

namespace JuniorEsiee\FinancesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use JMS\DiExtraBundle\Annotation\FormType;

/**
 * @FormType
 */
class DatepickerType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'widget' => 'single_text',
            'format' => 'dd/MM/yyyy',
            'attr' => array(
                'data-widget'=>'datePicker',
            ),
        ));
    }

    public function getParent()
    {
        return 'date';
    }

    public function getName()
    {
        return 'datepicker';
    }
}