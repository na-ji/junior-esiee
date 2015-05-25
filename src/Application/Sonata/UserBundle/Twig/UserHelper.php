<?php

namespace Application\Sonata\UserBundle\Twig;

use Application\Sonata\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Role\RoleHierarchy;
use Symfony\Component\Security\Core\Role\Role;

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
     * @var RoleHierarchy
     */
    private $roleHierarchy;

    /**
     * @InjectParams({
     *     "router"    = @Inject("router"),
     *     "em"        = @Inject("doctrine.orm.entity_manager"),
     *     "hierarchy" = @Inject("security.role_hierarchy")
     * })
     */
    function __construct(Router $router, EntityManager $em, RoleHierarchy $hierarchy)
    {
        $this->router        = $router;
        $this->em            = $em;
        $this->roleHierarchy = $hierarchy;
    }

    public function getFilters()
    {
        return array(
            'linkUser' => new \Twig_Filter_Method($this, 'linkUser', array('is_safe' => array('html'))),
            'md5'      => new \Twig_SimpleFilter('md5', 'md5'),
        );
    }

    public function getFunctions()
    {
        return array(
            'getUser'       => new \Twig_Function_Method($this, 'getUser'),
            'isUserGranted' => new \Twig_Function_Method($this, 'isUserGranted'),
        );
    }

    public function linkUser(User $user = null, array $attr = null)
    {
        if (null === $user)
        {
            $html = 'Aucun';
        } else {
            $html = "<a href='".$this->router->generate('je_user_users_profile', array('id' => $user->getId()), true)."'";

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

    public function isUserGranted(User $user, $checkRole)
    {
        $userRoles = $user->getRoles();

        if (in_array($checkRole, $userRoles))
        {
            return true;
        }

        foreach ($userRoles as $userRole) 
        {
            $roleOwnsRole = $this->roleOwnsRole($userRole, $checkRole);

            if ($roleOwnsRole) 
            {
                return true;
            }
        }
        return false;
     }

    private function roleOwnsRole($masterRole, $slaveRole)
    {
        if ($masterRole === $slaveRole)
        { 
            return true;
        }
        
        foreach ($this->roleHierarchy->getReachableRoles(array(new Role($masterRole))) as $role) {
            if ($role->getRole() === $slaveRole) {
                return true;
            }
        }
        return false;
    }

    public function getName()
    {
        return 'user_helper';
    }
}