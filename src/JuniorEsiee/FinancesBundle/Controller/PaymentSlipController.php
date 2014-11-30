<?php

namespace JuniorEsiee\FinancesBundle\Controller;

use JuniorEsiee\FinancesBundle\Entity\PaymentSlip;
use JuniorEsiee\FinancesBundle\Form\PaymentSlipType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Response;
use Knp\Component\Pager\Paginator;

class PaymentSlipController extends Controller
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
     * @var UploadableManager
     */
    private $uploadableManager;

    /**
     * @Secure(roles="ROLE_TREZ_VIEW")
     * @Template
     */
    public function indexAction($page, $sort, $direction)
    {
        $_GET['sort'] = $sort;
        $_GET['direction'] = $direction;
        $slips = $this->em->getRepository('JuniorEsieeFinancesBundle:PaymentSlip')->queryAll();
        $pagination = $this->paginator->paginate($slips, $page, 40);

        return array(
            'slips' => $pagination,
        );
    }

    /**
     * @Secure(roles="ROLE_TREZ_ADD")
     * @Template
     */
    public function newAction()
    {
        $slip = new PaymentSlip(
            $this->em->getRepository('JuniorEsieeFinancesBundle:Variable')->findOneBySlug('smic'),
            $this->em->getRepository('JuniorEsieeFinancesBundle:Variable')->findOneBySlug('urssaf_1_1'),
            $this->em->getRepository('JuniorEsieeFinancesBundle:Variable')->findOneBySlug('urssaf_1_2'),
            $this->em->getRepository('JuniorEsieeFinancesBundle:Variable')->findOneBySlug('urssaf_2_1'),
            $this->em->getRepository('JuniorEsieeFinancesBundle:Variable')->findOneBySlug('urssaf_2_2'),
            $this->em->getRepository('JuniorEsieeFinancesBundle:Variable')->findOneBySlug('urssaf_2_3'),
            $this->em->getRepository('JuniorEsieeFinancesBundle:Variable')->findOneBySlug('urssaf_2_4')
        );

        return $this->handleForm($slip);
    }

    /**
     * @Secure(roles="ROLE_TREZ_EDIT")
     * @Template
     */
    public function editAction(PaymentSlip $slip)
    {
        return $this->handleForm($slip);
    }

    private function handleForm(PaymentSlip $slip)
    {
        $form = $this->createForm(new PaymentSlipType, $slip);

        if($this->request->isMethod('POST')){
            $form->handleRequest($this->request);

            if($form->isValid()){
                $this->em->persist($slip);
                $this->em->flush();

                $this->request->getSession()->getFlashBag()->add('success', 'Votre bulletin de versement a bien été enregistré.');

                return $this->redirect($this->generateUrl('je_finances_bv'));
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }
}
