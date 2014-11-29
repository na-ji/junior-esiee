<?php

namespace Application\Sonata\UserBundle\Entity;

use Sonata\UserBundle\Entity\BaseGroup as BaseGroup;
use \Doctrine\Common\Collections\ArrayCollection;

class Group extends BaseGroup
{
    /**
     * @var integer $id
     */
    protected $id;

    /**
     *  @var ArrayCollection $users
     */
    private $users;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->roles = array('ROLE_USERS_VIEW', 'ROLE_GROUP_VIEW');
        $this->users = new ArrayCollection();
    }

    public function __toString()
    {
        return ''.$this->name;
    }

    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Add users
     *
     * @param User $users
     * @return Group
     */
    public function addUser(User $users)
    {
        $this->users[] = $users;
    
        return $this;
    }

    /**
     * Remove users
     *
     * @param User $users
     */
    public function removeUser(User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }
}