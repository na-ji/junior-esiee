<?php

namespace JuniorEsiee\NotificationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController extends Controller
{
    /** 
    * @var  EntityManager 
    */
    private $em;

    /**
     * @var SecurityContext
     */
    private $security;

    /**
     * @Secure(roles="ROLE_USER")
     */
    public function markAllAsReadAction()
    {
    	$this->em->getRepository('JuniorEsieeNotificationBundle:Notification')->markAllAsRead($this->security->getToken()->getUser());
    	return new JsonResponse(true);
    }
}
