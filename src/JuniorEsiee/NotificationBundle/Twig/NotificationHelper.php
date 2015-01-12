<?php

namespace JuniorEsiee\NotificationBundle\Twig;

use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\Tag;
use JMS\DiExtraBundle\Annotation\InjectParams;
use Application\Sonata\UserBundle\Entity\User;

/**
 * @Service()
 * @Tag("twig.extension")
 */
class NotificationHelper extends \Twig_Extension
{
    /** @var  EntityManager */
    private $em;

    /** @var  \Twig_Environment */
    private $twig;

    /**
     * @InjectParams({
     *     "em" = @Inject("doctrine.orm.entity_manager"),
     *     "twig" = @Inject("twig")
     * })
     */
    function __construct(EntityManager $em, \Twig_Environment $twig)
    {
        $this->em   = $em;
        $this->twig = $twig;
    }

    public function getFunctions()
    {
        return array(
            'getNofications' => new \Twig_Function_Method($this, 'getNofications'),
            'countUnreadNotifications' => new \Twig_Function_Method($this, 'countUnreadNotifications'),
        );
    }

    public function getNofications(User $user)
    {
        $notifications = $this->em->getRepository('JuniorEsieeNotificationBundle:Notification')->findBy(array('user' => $user), array('date' => 'DESC'));
        foreach ($notifications as $notification) {
            $notification->safeMessage = $this->twig->render($notification->getMessage(), array());
        }
        return $notifications;
    }

    public function countUnreadNotifications(User $user)
    {
        return $this->em->getRepository('JuniorEsieeNotificationBundle:Notification')->countUnreadNotifications($user);
    }

    public function getName()
    {
        return 'notification_helper';
    }
}