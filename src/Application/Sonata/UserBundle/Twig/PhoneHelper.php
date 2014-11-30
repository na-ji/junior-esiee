<?php

namespace Application\Sonata\UserBundle\Twig;

use Application\Sonata\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;

/**
 * @Service
 * @Tag("twig.extension")
 */
class PhoneHelper extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'phone' => new \Twig_Filter_Method($this, 'phone'),
        );
    }

    public function phone($phone, array $attr = null)
    {
        if (null === $phone || strlen($phone) < 9)
            return $phone;
        if(strlen($phone) == 9)
            $phone = '0'.$phone;

        return chunk_split($phone, 2, ' ');
    }

    public function getName()
    {
        return 'phone_helper';
    }
}