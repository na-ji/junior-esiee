<?php

namespace Application\Sonata\UserBundle\Entity;

/**
 * UserSkill
 *
 */
class UserSkill
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $level = 0;

    private $level_string = array('Jamais', 'Débutant', 'Intermédiaire', 'Avancé');

    /**
     * @var \Application\Sonata\UserBundle\Entity\User
     */
    private $user;

    /**
     * @var \Application\Sonata\UserBundle\Entity\Skill
     */
    private $skill;

    /**
     * Set level
     *
     * @param integer $level
     * @return UserSkill
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return integer 
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Get level string
     *
     * @return string 
     */
    public function getLevelString()
    {
        return $this->level_string[$this->level];
    }

    /**
     * Set user
     *
     * @param \Application\Sonata\UserBundle\Entity\User $user
     * @return UserSkill
     */
    public function setUser(\Application\Sonata\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Application\Sonata\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set skill
     *
     * @param \Application\Sonata\UserBundle\Entity\Skill $skill
     * @return UserSkill
     */
    public function setSkill(\Application\Sonata\UserBundle\Entity\Skill $skill)
    {
        $this->skill = $skill;

        return $this;
    }

    /**
     * Get skill
     *
     * @return \Application\Sonata\UserBundle\Entity\Skill 
     */
    public function getSkill()
    {
        return $this->skill;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    public function __toString()
    {
        if (null !== $this->skill)
            return ''.$this->skill->getName().' : '.$this->getLevelString();
        return 'Nouveau';
    }
}
