<?php

namespace Application\Sonata\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Skill
 */
class Skill
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    private $category;

    public function __toString()
    {
        return ''.$this->name;
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

    /**
     * Set name
     *
     * @param string $name
     * @return Skill
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set category
     *
     * @param \Application\Sonata\UserBundle\Entity\SkillCategory $category
     * @return Skill
     */
    public function setCategory(\Application\Sonata\UserBundle\Entity\SkillCategory $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Application\Sonata\UserBundle\Entity\SkillCategory 
     */
    public function getCategory()
    {
        return $this->category;
    }
}
