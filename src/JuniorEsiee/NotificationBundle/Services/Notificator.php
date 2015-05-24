<?php

namespace JuniorEsiee\NotificationBundle\Services;

use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use Application\Sonata\UserBundle\Entity\User;
use JuniorEsiee\NotificationBundle\Entity\Notification;

/**
 * @Service("je.notification.notificator")
 */
class Notificator
{
    /** @var  EntityManager */
    private $em;

    private $mailer;

    /** @var  \Twig_Environment */
    private $twig;

    /**
     * @InjectParams({
     *     "em"     = @Inject("doctrine.orm.entity_manager"),
     *     "mailer" = @Inject("mailer"),
     *     "twig" = @Inject("twig"),
     * })
     */
    function __construct(EntityManager $em, $mailer, \Twig_Environment $twig)
    {
        $this->em     = $em;
        $this->mailer = $mailer;
        $this->twig   = $twig;
    }

    public function notify(User $user, $message, $mail_subject = null, $mail_body = null)
    {
        $notification = new Notification();
        $notification
            ->setUser($user)
            ->setMessage($message)
        ;
        $this->em->persist($notification);
        $this->em->flush();

        if (null !== $mail_subject && null !== $mail_body)
        {
            $mail_body .= '<br /><br />Cordialement,<br /><a href="{{ url(\'page_tool_index\') }}">L\'Outil Junior ESIEE</a>';
            $mail = \Swift_Message::newInstance()
                ->setSubject($mail_subject)
                ->setFrom(array("ne-pas-repondre@junioresiee.com" => "L'Outil Junior ESIEE"))
                ->setTo($user->getEmail())
                ->setContentType('text/html')
                ->setBody($this->twig->render($mail_body, array()))
            ;
            
            $response = $this->mailer->send($mail);
        }
    }
}