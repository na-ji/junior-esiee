<?php

namespace Application\Sonata\UserBundle\Controller;

use Doctrine\ORM\EntityManager;
use Application\Sonata\UserBundle\Entity\User;
use Application\Sonata\UserBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use Knp\Component\Pager\Paginator;

class UserController extends Controller
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
     * @Secure(roles="ROLE_USERS_VIEW")
     * @Template
     */
    public function indexAction($page)
    {
        $users = $this->em->getRepository('ApplicationSonataUserBundle:User')->queryAll();
        $pagination = $this->paginator->paginate($users, $page, 40);

        return array(
            'users' => $pagination,
        );
    }

    /**
     * @Secure(roles="ROLE_USERS_ADD")
     * @Template
     */
    public function newAction(Request $request)
    {
        $user = new User();
        $user->setEnabled(true);

        return $this->handleForm($user, $request);
    }

    /**
     * @Secure(roles="ROLE_USERS_EDIT")
     * @Template
     */
    public function editAction(User $user, Request $request)
    {
        return $this->handleForm($user, $request);
    }

    /**
     * @Secure(roles="ROLE_USERS_VIEW")
     * @Template
     */
    public function profileAction(User $user)
    {
        return array(
            'user' => $user,
        );
    }

    private function handleForm(User $user, Request $request)
    {
        $form = $this->createForm(new UserType, $user);

        if($this->request->isMethod('POST')){
            $form->handleRequest($this->request);

            if($form->isValid()){
                $this->em->persist($user);
                $this->em->flush();

                $request->getSession()->getFlashBag()->add('success', $user->getUsername().' a bien été enregistré.');

                return $this->redirect($this->generateUrl('je_user_users'));
            }
        }

        return array(
            'form' => $form->createView(),
            'user' => $user,
        );
    }
}
