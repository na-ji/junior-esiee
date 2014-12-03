<?php

namespace Application\Sonata\UserBundle\Mailer;

use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Mailer\Mailer as BaseMailer;

class Mailer extends BaseMailer
{
    public function sendCreatingPasswordEmailMessage(UserInterface $user)
    {
		$url      = $this->router->generate('fos_user_resetting_reset', array('token' => $user->getConfirmationToken()), true);
		$rendered = $this->templating->render('ApplicationSonataUserBundle:Mail:creatingPassword.txt.twig', array(
			'user'            => $user,
			'confirmationUrl' => $url
        ));
        $this->sendEmailMessage($rendered, $this->parameters['from_email']['resetting'], $user->getEmail());
    }
}
