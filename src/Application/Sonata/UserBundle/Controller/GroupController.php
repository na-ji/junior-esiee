<?php

namespace Application\Sonata\UserBundle\Controller;

use Doctrine\ORM\EntityManager;
use Application\Sonata\UserBundle\Entity\Group;
use Application\Sonata\UserBundle\Form\GroupType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use \FOS\UserBundle\Model\GroupManagerInterface;

class GroupController extends Controller
{
    /** @var  EntityManager */
    private $em;

    /** @var  Request */
    private $request;

    /** @var GroupManagerInterface */
    private $gm;

    /**
     * @Secure(roles="ROLE_GROUP_VIEW")
     * @Template
     */
    public function indexAction()
    {
        $groups = $this->em->getRepository('ApplicationSonataUserBundle:Group')->findAll();

        return array(
            'groups' => $groups,
        );
    }

    /**
     * @Secure(roles="ROLE_GROUP_ADD")
     * @Template
     */
    public function newAction()
    {
        /** @var Group $group */
        $group = $this->gm->createGroup('');

        return $this->handleForm($group);
    }

    /**
     * @Secure(roles="ROLE_GROUP_EDIT")
     * @Template
     */
    public function editAction(Group $group)
    {
        return $this->handleForm($group);
    }

    private function handleForm(Group $group)
    {
        $form = $this->createForm(new GroupType, $group);

        if($this->request->isMethod('POST')){
            $form->handleRequest($this->request);

            if($form->isValid()){

                $this->gm->updateGroup($group);

                return $this->redirect($this->generateUrl('je_user_groups'));
            }
        }

        return array(
            'form'  => $form->createView(),
            'group' => $group,
        );
    }
}
