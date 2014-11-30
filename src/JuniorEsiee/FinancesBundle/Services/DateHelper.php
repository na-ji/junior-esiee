<?php

namespace JuniorEsiee\FinancesBundle\Services;

use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;

/**
 * @Service("je.finances.date_helper")
 */
class DateHelper
{
    /** @var  EntityManager */
    private $em;

    private $month = null;

    private $year = null;

    /**
     * @InjectParams({
     *     "em" = @Inject("doctrine.orm.entity_manager")
     * })
     */
    function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getYearRange(array $entities)
    {
        $max = $min = intval((new \DateTime())->format('Y'));

        foreach($entities as $entity){
            $range = $this->em->getRepository($entity)->yearRange();
            $max = max(array_filter(array($max, $range['max'])));
            $min = min(array_filter(array($min, $range['min'])));
        }

        return array(
            'max' => $max,
            'min'=> $min,
        );
    }

    public function getDefaultDate($month, $year, array $yearRange)
    {
        $this->month = $month = $month === null ? (new \DateTime())->format('n') : $month;
        $this->year = $year = $year === null ? (new \DateTime())->format('Y') : max(min($year, $yearRange['max']), $yearRange['min']);

        return array($month, $year);
    }

    public function dataFromMonth($entity, $month = null, $year = null)
    {
        $month = $month === null ? $this->month : $month;
        $year =  $year  === null ? $this->year  : $year;

        return array(
            $this->em->getRepository($entity)->findFromMonth($month, $year),
            $this->em->getRepository($entity)->sumFromMonth($month, $year),
        );
    }
}