<?php

namespace JuniorEsiee\FinancesBundle\Controller;

use JuniorEsiee\FinancesBundle\Entity\Variable;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

class VariableController extends Controller
{
    /** @var  EntityManager */
    private $em;

    /** @var  Request */
    private $request;

    /**
     * @Secure(roles="ROLE_TREZ_EDIT")
     * @Template
     */
    public function indexAction()
    {
        $vars = $this->em->getRepository('JuniorEsieeFinancesBundle:Variable')->findAll();

        if($this->request->isMethod('POST')){
            foreach($vars as $var){
                /** @var Variable $var */
                $var->setValue($_POST[$var->getSlug()]);
                $this->em->persist($var);
            }
            $this->em->flush();

            $this->request->getSession()->getFlashBag()->add('success', 'Les variables ont bien été mises-à-jour.');
        }

        return array(
            'vars' => $vars,
        );
    }
}
