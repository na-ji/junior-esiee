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

    public function notify(User $user, $message, $mail = false)
    {
        $notification = new Notification();
        $notification
            ->setUser($user)
            ->setMessage($message)
        ;
        $this->em->persist($notification);
        $this->em->flush();
        if ($mail)
        {
            $mail = \Swift_Message::newInstance()
                ->setSubject("Notification depuis l'outil")
                ->setFrom(array("ne-pas-repondre@junioresiee.com" => "l'Outil"))
                ->setTo($user->getEmail())
                ->setContentType('text/html')
                ->setBody($this->twig->render($message, array()))
            ;
            
            $response = $this->mailer->send($mail);
        }
    }
}