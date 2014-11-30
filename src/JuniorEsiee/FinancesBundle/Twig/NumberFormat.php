<?php

namespace JuniorEsiee\FinancesBundle\Twig;

use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;

/**
 * @Service
 * @Tag("twig.extension")
 */
class NumberFormat extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'money' => new \Twig_Filter_Method($this, 'money', array('is_safe' => array('html'))),
        );
    }

    public function money($number, $unit = '€', $decimals = 2, $decPoint = ',', $thousandsSep = ' ', $colors = true)
    {
        $result = number_format($number, $decimals, $decPoint, $thousandsSep).($unit == null ? '': ' '.$unit);

        if($colors){
            if($number < 0)
                $result = "<span style='color: #A95252'>$result</span>";

            if($number == 0)
                $result = "<span style='opacity: .5'>$result</span>";
        }

        return $result;
    }

    public function getName()
    {
        return 'number_format';
    }
}
