<?php

namespace JuniorEsiee\FinancesBundle\Controller;

use JuniorEsiee\FinancesBundle\Services\DateHelper;
use JuniorEsiee\StatBundle\Entity\DateRange;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;

class UrssafController extends Controller
{
    /** @var  EntityManager */
    private $em;

    /** @var  Request */
    private $request;

    /**
     * @Secure(roles="ROLE_TREZ_VIEW")
     * @Template
     */
    public function monthAction($month, $year)
    {
        /** @var DateHelper $dateHelper */
        $dateHelper = $this->get('je.finances.date_helper');

        $yearRange = $dateHelper->getYearRange(array(
            'JuniorEsieeFinancesBundle:PaymentSlip',
        ));

        list($month, $year)                     = $dateHelper->getDefaultDate($month, $year, $yearRange);
        list($paymentSlips, $paymentSlipsSum)   = $dateHelper->dataFromMonth('JuniorEsieeFinancesBundle:PaymentSlip');

        return array(
            'month' => $month,
            'year' => $year,
            'dateRange' => $yearRange,
            'paymentSlips' => $paymentSlips,
            'paymentSlipsSum' => $paymentSlipsSum,
        );
    }

    /**
     * @Secure(roles="ROLE_TREZ_VIEW")
     * @Template
     */
    public function yearAction($year)
    {
        /** @var DateHelper $dateHelper */
        $dateHelper = $this->get('je.finances.date_helper');

        $yearRange = $dateHelper->getYearRange(array(
            'JuniorEsieeFinancesBundle:PaymentSlip',
        ));

        list(, $year) = $dateHelper->getDefaultDate(null, $year, $yearRange);
        $dateRange = new DateRange($year);
        $paymentSlips = $this->em->getRepository('JuniorEsieeFinancesBundle:PaymentSlip')->sumFromRange($dateRange);

        return array(
            'year' => $year,
            'dateRange' => $yearRange,
            'paymentSlips' => $paymentSlips,
        );
    }
}
