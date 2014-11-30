<?php

namespace JuniorEsiee\StatBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContextInterface;

/**
 * @Assert\Callback(methods={"checkDates"})
 */
class DateRange
{
    private $from;

    private $to;

    public function __construct($year = null)
    {
        $this->to = new \DateTime;
        $this->from = new \DateTime;

        $this->to->setDate($this->to->format('Y'), $this->to->format('n'), 1)->setTime(23, 59, 59)->add(new \DateInterval('P1M'))->sub(new \DateInterval('P1D'));
        $this->from->setDate($this->to->format('Y'), $this->to->format('n'), 1)->setTime(0, 0)->sub(new \DateInterval('P6M'));

        if($year !== null){
            $this->setMonthFrom(1);
            $this->setYearFrom($year);

            $this->setMonthTo(12);
            $this->setYearTo($year);
        }
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function getTo()
    {
        return $this->to;
    }

    public function setMonthFrom($month)
    {
        $this->from->setDate($this->from->format('Y'), $month, 1);

        return $this;
    }

    public function getMonthFrom()
    {
        return intval($this->from->format('n'));
    }

    public function setYearFrom($year)
    {
        $this->from->setDate($year, $this->from->format('m'), 1);

        return $this;
    }

    public function getYearFrom()
    {
        return intval($this->from->format('Y'));
    }

    public function setMonthTo($month)
    {
        $this->to->setDate($this->to->format('Y'), $month, 1);

        return $this;
    }

    public function getMonthTo()
    {
        return intval($this->to->format('n'));
    }

    public function setYearTo($year)
    {
        $this->to->setDate($year, $this->to->format('m'), 1);

        return $this;
    }

    public function getYearTo()
    {
        return intval($this->to->format('Y'));
    }

    public function getMonthsArray()
    {
        $months = array(
            1 => 'Jan',
            'Fév',
            'Mars',
            'Avr',
            'Mai',
            'Juin',
            'Juil',
            'Août',
            'Sept',
            'Oct',
            'Nov',
            'Déc',
        );

        $list = array();

        for($year = $this->getYearFrom(); $year <= $this->getYearTo(); ++$year)
            for($month = ($year == $this->getYearFrom() ? $this->getMonthFrom() : 1); $month <= ($year == $this->getYearTo() ? $this->getMonthTo() : 12); ++$month)
                $list[$year.$month] = "'{$months[$month]} $year'";

        return $list;
    }

    public function getMonths()
    {
        return implode(',', $this->getMonthsArray());
    }

    public function checkDates(ExecutionContextInterface $context)
    {
        if($this->from->diff($this->to)->invert === 1)
            $context->addViolation("La date de fin ne peut pas être avant la date de début");
    }
}