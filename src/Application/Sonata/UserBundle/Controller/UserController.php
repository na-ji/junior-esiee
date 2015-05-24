<?php

namespace Application\Sonata\UserBundle\Controller;

use Doctrine\ORM\EntityManager;
use Application\Sonata\UserBundle\Entity\User;
use Application\Sonata\UserBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\DiExtraBundle\Annotation\Inject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use Knp\Component\Pager\Paginator;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use FOS\UserBundle\Mailer\MailerInterface;

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
     * @Inject("fos_user.util.token_generator")
     */
    protected $tokenGenerator;

    /**
     * @Inject("application.sonata.user.mailer")
     */
    protected $mailer;

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

        return $this->handleForm($user, $request, true);
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
        $projectsAsStudents   = $this->em->getRepository('JuniorEsieeBusinessBundle:Project')->findByStudents($user);
        $projectsAsCommercial = $this->em->getRepository('JuniorEsieeBusinessBundle:Project')->findBy(array('commercial' => $user), array('depositDate' => 'DESC'));
        $projectsAsRbu        = $this->em->getRepository('JuniorEsieeBusinessBundle:Project')->findBy(array('rbu' => $user), array('depositDate' => 'DESC'));

        return array(
            'user'                 => $user,
            'projectsAsStudents'   => $projectsAsStudents,
            'projectsAsCommercial' => $projectsAsCommercial,
            'projectsAsRbu'        => $projectsAsRbu,
        );
    }

    private function handleForm(User $user, Request $request, $new = false)
    {
        $form = $this->createForm(new UserType, $user);

        if($this->request->isMethod('POST')){
            $form->handleRequest($this->request);

            if($form->isValid()){
                if($new)
                {
                    $token = $this->tokenGenerator->generateToken();
                    $user->setConfirmationToken($token);
                    $user->setPlainPassword($token);
                    $user->setHasPassword(false);
                    $user->setEnabled(false);
                    $user->setPasswordRequestedAt(new \DateTime());
                    $this->mailer->sendCreatingPasswordEmailMessage($user);
                }
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
