<?php

namespace Application\Sonata\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SkillType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => array(
                '0' => '&empty;',
                '1' => 'Débutant',
                '2' => 'Intermédiaire',
                '3' => 'Avancé',
            ),
			'required' => true,
			'expanded' => true,
			// 'data'     => '0',
			// 'empty_value' => '&empty;',
			// 'empty_data' => '0',
			'attr'     => array(
				'data-sonata-icheck' => 'false'
			),
        ));
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'skill';
    }
}