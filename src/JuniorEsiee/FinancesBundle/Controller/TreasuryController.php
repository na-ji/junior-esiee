<?php

namespace JuniorEsiee\FinancesBundle\Controller;

use JuniorEsiee\FinancesBundle\Services\DateHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;

class TreasuryController extends Controller
{
    /** @var  EntityManager */
    private $em;

    /** @var  Request */
    private $request;

    /**
     * @Secure(roles="ROLE_TREZ_VIEW")
     * @Template
     */
    public function indexAction($month, $year)
    {
        /** @var DateHelper $dateHelper */
        $dateHelper = $this->get('je.finances.date_helper');

        $yearRange = $dateHelper->getYearRange(array(
            'JuniorEsieeFinancesBundle:CustomerInvoice',
            'JuniorEsieeFinancesBundle:PaymentSlip',
            'JuniorEsieeFinancesBundle:SupplierInvoice',
        ));

        list($month, $year)                     = $dateHelper->getDefaultDate($month, $year, $yearRange);
        list($sales, $salesSum)                 = $dateHelper->dataFromMonth('JuniorEsieeFinancesBundle:CustomerInvoice');
        list($purchases, $purchasesSum)         = $dateHelper->dataFromMonth('JuniorEsieeFinancesBundle:SupplierInvoice');
        list($paymentSlips, $paymentSlipsSum)   = $dateHelper->dataFromMonth('JuniorEsieeFinancesBundle:PaymentSlip');

        return array(
            'month' => $month,
            'year' => $year,
            'dateRange' => $yearRange,
            'sales' => $sales,
            'salesSum' => $salesSum,
            'purchases' => $purchases,
            'purchasesSum' => $purchasesSum,
            'paymentSlips' => $paymentSlips,
            'paymentSlipsSum' => $paymentSlipsSum,
        );
    }
}
