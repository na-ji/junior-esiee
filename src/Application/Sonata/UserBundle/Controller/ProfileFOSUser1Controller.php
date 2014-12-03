<?php

namespace Application\Sonata\UserBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\FOSUserEvents;
use Sonata\UserBundle\Controller\ProfileFOSUser1Controller as BaseController;
use Application\Sonata\UserBundle\Entity\User;
use Application\Sonata\UserBundle\Form\UserType;

/**
 * This class is inspired from the FOS Profile Controller, except :
 *   - only twig is supported
 *   - separation of the user authentication form with the profile form
 */
class ProfileFOSUser1Controller extends BaseController
{
    /** @var  EntityManager */
    private $em;

    /** @var  Request */
    private $request;

    /**
     * @return Response
     *
     * @throws AccessDeniedException
     */
    public function editAuthenticationAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $form = $this->container->get('sonata.user.authentication.form');
        $formHandler = $this->container->get('sonata.user.authentication.form_handler');

        $process = $formHandler->process($user);
        if ($process) {
            $this->setFlash('success', $this->get('translator')->trans('profile.flash.updated', array(), 'SonataUserBundle'));

            return new RedirectResponse($this->generateUrl('sonata_user_profile_show'));
        }

        return $this->render('ApplicationSonataUserBundle:Profile:edit_authentication.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @return Response
     *
     * @throws AccessDeniedException
     */
    public function editProfileAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $form = $this->createForm(new UserType(true), $user);
        // $formHandler = $this->container->get('sonata.user.profile.form.handler');

        // $process = $formHandler->process($user);
        // if ($process) {
        //     $this->setFlash('success', $this->get('translator')->trans('profile.flash.updated', array(), 'SonataUserBundle'));

        //     return new RedirectResponse($this->generateUrl('sonata_user_profile_show'));
        // }

        if($this->request->isMethod('POST')) {
            $form->handleRequest($this->request);

            if($form->isValid()){
                $this->em->persist($user);
                $this->em->flush();

                $this->setFlash('success', $this->get('translator')->trans('profile.flash.updated', array(), 'SonataUserBundle'));

                //return new RedirectResponse($this->generateUrl('sonata_user_profile_show'));
            }
        }

        return $this->render('ApplicationSonataUserBundle:Profile:edit_profile.html.twig', array(
            'form'               => $form->createView(),
            'user'               => $user,
            'breadcrumb_context' => 'user_profile',
        ));
    }
}
