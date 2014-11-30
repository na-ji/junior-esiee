<?php

namespace JuniorEsiee\FinancesBundle\Controller;

use JuniorEsiee\FinancesBundle\Entity\SupplierInvoice;
use JuniorEsiee\FinancesBundle\Form\SupplierInvoiceType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Response;
use Knp\Component\Pager\Paginator;

class SupplierInvoiceController extends Controller
{
    /** @var  EntityManager */
    private $em;

    /** @var  Request */
    private $request;

    /**
     * @var Paginator
     */
    private $paginator;

    /**
     * @Secure(roles="ROLE_TREZ_VIEW")
     * @Template
     */
    public function indexAction($page, $sort, $direction)
    {
        $_GET['sort'] = $sort;
        $_GET['direction'] = $direction;
        $invoices = $this->em->getRepository('JuniorEsieeFinancesBundle:SupplierInvoice')->queryAll();
        $pagination = $this->paginator->paginate($invoices, $page, 40);

        return array(
            'invoices' => $pagination,
        );
    }

    /**
     * @Secure(roles="ROLE_TREZ_ADD")
     * @Template
     */
    public function newAction()
    {
        $invoice = new SupplierInvoice;

        return $this->handleForm($invoice);
    }

    /**
     * @Secure(roles="ROLE_TREZ_EDIT")
     * @Template
     */
    public function editAction(SupplierInvoice $invoice)
    {
        return $this->handleForm($invoice);
    }

    private function handleForm(SupplierInvoice $invoice)
    {
        $form = $this->createForm(new SupplierInvoiceType, $invoice);

        if($this->request->isMethod('POST')){
            $form->handleRequest($this->request);

            if($form->isValid()){
                $this->em->persist($invoice);
                $this->em->flush();

                $this->request->getSession()->getFlashBag()->add('success', 'Votre facture fournisseur / RF a bien été enregistrée.');

                return $this->redirect($this->generateUrl('je_finances_ff'));
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }
}
