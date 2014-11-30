<?php

namespace JuniorEsiee\StatBundle\Controller;

use JE\FinancesBundle\Services\DateHelper;
use JuniorEsiee\StatBundle\Entity\DateRange;
use JuniorEsiee\StatBundle\Form\DateRangeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;

class FinancesController extends Controller
{
    /** @var  EntityManager */
    private $em;

    /** @var  Request */
    private $request;

    /**
     * @Secure(roles="ROLE_TREZ_VIEW")
     * @Template
     */
    public function treasuryAction()
    {
        /** @var DateHelper $dateHelper */
        $dateHelper = $this->get('je.finances.date_helper');

        $yearRange = $dateHelper->getYearRange(array(
            'JuniorEsieeFinancesBundle:CustomerInvoice',
            'JuniorEsieeFinancesBundle:PaymentSlip',
            'JuniorEsieeFinancesBundle:SupplierInvoice',
        ));

        $dateRange = new DateRange;
        $form = $this->createForm(new DateRangeType($this->get('junior_esiee.finances_bundle.twig.date_format')->months(), range($yearRange['min'], $yearRange['max'])), $dateRange);

        if($this->request->isMethod('POST')){
            $form->handleRequest($this->request);
        }

        if($form->isValid() || $this->request->isMethod('GET')){
            $sales = $this->em->getRepository('JuniorEsieeFinancesBundle:CustomerInvoice')->sumFromRange($dateRange);
            $paymentSlips = $this->em->getRepository('JuniorEsieeFinancesBundle:PaymentSlip')->sumFromRange($dateRange);
            $purchases = $this->em->getRepository('JuniorEsieeFinancesBundle:SupplierInvoice')->sumFromRange($dateRange);
        }
        else {
            $sales = null;
            $paymentSlips = null;
            $purchases = null;
        }

        return array(
            'dateRange' => $dateRange,
            'form' => $form->createView(),
            'sales' => $sales,
            'paymentSlips' => $paymentSlips,
            'purchases' => $purchases,
        );
    }
}
