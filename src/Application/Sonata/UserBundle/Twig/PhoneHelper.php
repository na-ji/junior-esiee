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
        if(strlen($phone) === 9)
            $phone = '0'.$phone;
        if(strlen($phone) === 10)
            return preg_replace('#^0([0-9])([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})$#', '+33 (0)$1 $2 $3 $4 $5', $phone);
        if(strlen($phone) === 11)
            return preg_replace('#^([0-9]{2})([0-9])([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})$#', '+$1 (0)$2 $3 $4 $5 $6', $phone);
        if(strlen($phone) === 12)
            return preg_replace('#^\+([0-9]{2})([0-9])([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})$#', '+$1 (0)$2 $3 $4 $5 $6', $phone);
        return preg_replace('#^([0-9]{2})0([0-9])([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})$#', '+$1 (0)$2 $3 $4 $5 $6', $phone);
    }

    public function getName()
    {
        return 'phone_helper';
    }
}