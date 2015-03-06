<?php

namespace Application\Sonata\UserBundle\Twig;

use Application\Sonata\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use Doctrine\ORM\EntityManager;

/**
 * @Service
 * @Tag("twig.extension")
 */
class UserHelper extends \Twig_Extension
{
    /**
     * @var Router
     */
    protected $router;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @InjectParams({
     *     "router" = @Inject("router"),
     *     "em" = @Inject("doctrine.orm.entity_manager")
     * })
     */
    function __construct(Router $router, EntityManager $em)
    {
        $this->router = $router;
        $this->em     = $em;
    }

    public function getFilters()
    {
        return array(
            'linkUser' => new \Twig_Filter_Method($this, 'linkUser', array('is_safe' => array('html'))),
        );
    }

    public function getFunctions()
    {
        return array(
            'getUser' => new \Twig_Function_Method($this, 'getUser'),
        );
    }

    public function linkUser(User $user = null, array $attr = null)
    {
        if (null === $user)
        {
            $html = 'Aucun';
        } else {
            $html = "<a href='".$this->router->generate('je_user_users_profile', array('id'=>$user->getId()))."'";

            if($attr !== null)
                foreach($attr as $key => $value)
                    $html .= " $key='$value'";

            $html .= ">$user</a>";
        }

        return $html;
    }

    public function getUser($id)
    {
        return $this->em->getRepository('ApplicationSonataUserBundle:User')->find($id);
    }

    public function getName()
    {
        return 'user_helper';
    }
}