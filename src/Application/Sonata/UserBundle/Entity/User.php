<?php

namespace Application\Sonata\UserBundle\Entity;

use Sonata\UserBundle\Entity\BaseUser as BaseUser;

class User extends BaseUser
{
    /**
     * @var integer $id
     */
    protected $id;

    protected $isComplete;

    protected $registredAt;

    protected $promo;

    protected $address;

    protected $zipCode;

    protected $city;

    protected $birthCity;

    protected $birthDepartment;

    protected $nationality;

    protected $socialSecurityNumber;

    protected $socialSecurityCenter;

    protected $cv;

    protected $socialSecurity;

    protected $chequeNumber;

    protected $hasPassword = false;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $skills;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $skillCategories;

    /**
     * @var Application\Sonata\UserBundle\Entity\Group
     */
    private $group;

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
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->registredAt = new \DateTime();
    }

    public function __toString()
    {
        return $this->lastname.' '.$this->firstname;
    }

    /**
     * Set isComplete
     *
     * @param boolean $isComplete
     * @return User
     */
    public function setIsComplete($isComplete)
    {
        $this->isComplete = $isComplete;

        return $this;
    }

    /**
     * Get isComplete
     *
     * @return boolean 
     */
    public function getIsComplete()
    {
        return $this->isComplete;
    }

    /**
     * Set promo
     *
     * @param string $promo
     * @return User
     */
    public function setPromo($promo)
    {
        $this->promo = $promo;

        return $this;
    }

    /**
     * Get promo
     *
     * @return string 
     */
    public function getPromo()
    {
        return $this->promo;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return User
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set zipCode
     *
     * @param integer $zipCode
     * @return User
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    /**
     * Get zipCode
     *
     * @return integer 
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return User
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set birthCity
     *
     * @param string $birthCity
     * @return User
     */
    public function setBirthCity($birthCity)
    {
        $this->birthCity = $birthCity;

        return $this;
    }

    /**
     * Get birthCity
     *
     * @return string 
     */
    public function getBirthCity()
    {
        return $this->birthCity;
    }

    /**
     * Set birthDepartment
     *
     * @param string $birthDepartment
     * @return User
     */
    public function setBirthDepartment($birhDepartment)
    {
        $this->birhDepartment = $birhDepartment;

        return $this;
    }

    /**
     * Get birthDepartment
     *
     * @return string 
     */
    public function getBirthDepartment()
    {
        return $this->birthDepartment;
    }

    /**
     * Set nationality
     *
     * @param string $nationality
     * @return User
     */
    public function setNationality($nationality)
    {
        $this->nationality = $nationality;

        return $this;
    }

    /**
     * Get nationality
     *
     * @return string 
     */
    public function getNationality()
    {
        return $this->nationality;
    }

    /**
     * Set socialSecurityNumber
     *
     * @param string $socialSecurityNumber
     * @return User
     */
    public function setSocialSecurityNumber($socialSecurityNumber)
    {
        $this->socialSecurityNumber = $socialSecurityNumber;

        return $this;
    }

    /**
     * Get socialSecurityNumber
     *
     * @return string 
     */
    public function getSocialSecurityNumber()
    {
        return $this->socialSecurityNumber;
    }

    /**
     * Set socialSecurityCenter
     *
     * @param string $socialSecurityCenter
     * @return User
     */
    public function setSocialSecurityCenter($socialSecurityCenter)
    {
        $this->socialSecurityCenter = $socialSecurityCenter;

        return $this;
    }

    /**
     * Get socialSecurityCenter
     *
     * @return string 
     */
    public function getSocialSecurityCenter()
    {
        return $this->socialSecurityCenter;
    }

    /**
     * Set chequeNumber
     *
     * @param integer $chequeNumber
     * @return User
     */
    public function setChequeNumber($chequeNumber)
    {
        $this->chequeNumber = $chequeNumber;

        return $this;
    }

    /**
     * Get chequeNumber
     *
     * @return integer 
     */
    public function getChequeNumber()
    {
        return $this->chequeNumber;
    }

    /**
     * Set cv
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $cv
     * @return User
     */
    public function setCv(\Application\Sonata\MediaBundle\Entity\Media $cv = null)
    {
        $this->cv = $cv;

        return $this;
    }

    /**
     * Get cv
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media 
     */
    public function getCv()
    {
        return $this->cv;
    }

    /**
     * Set socialSecurity
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $socialSecurity
     * @return User
     */
    public function setSocialSecurity(\Application\Sonata\MediaBundle\Entity\Media $socialSecurity = null)
    {
        $this->socialSecurity = $socialSecurity;

        return $this;
    }

    /**
     * Get socialSecurity
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media 
     */
    public function getSocialSecurity()
    {
        return $this->socialSecurity;
    }


    /**
     * Set registredAt
     *
     * @param \DateTime $registredAt
     * @return User
     */
    public function setRegistredAt($registredAt)
    {
        $this->registredAt = $registredAt;

        return $this;
    }

    /**
     * Get registredAt
     *
     * @return \DateTime 
     */
    public function getRegistredAt()
    {
        return $this->registredAt;
    }

    /**
     * Set hasPassword
     *
     * @param boolean $hasPassword
     * @return User
     */
    public function setHasPassword($hasPassword)
    {
        $this->hasPassword = $hasPassword;

        return $this;
    }

    /**
     * Get hasPassword
     *
     * @return boolean 
     */
    public function getHasPassword()
    {
        return $this->hasPassword;
    }

    /**
     * @param mixed $group
     */
    public function setGroup($group)
    {
        $this->group = $group;
    }

    /**
     * @return mixed
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @return boolean
     */
    public function isGranted($role)
    {
        return in_array($role, $this->getRoles());
    }

    /**
     * @return array|void
     */
    public function getRoles()
    {
        return array_unique(array_merge(parent::getRoles(), $this->getGroup()->getRoles()));
    }


    /**
     * Add skillCategories
     *
     * @param \Application\Sonata\UserBundle\Entity\SkillCategory $skillCategories
     * @return User
     */
    public function addSkillCategory(\Application\Sonata\UserBundle\Entity\SkillCategory $skillCategories)
    {
        $this->skillCategories[] = $skillCategories;

        return $this;
    }

    /**
     * Remove skillCategories
     *
     * @param \Application\Sonata\UserBundle\Entity\SkillCategory $skillCategories
     */
    public function removeSkillCategory(\Application\Sonata\UserBundle\Entity\SkillCategory $skillCategories)
    {
        $this->skillCategories->removeElement($skillCategories);
    }

    /**
     * Get skillCategories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSkillCategories()
    {
        return $this->skillCategories;
    }

    /**
     * Add skills
     *
     * @param \Application\Sonata\UserBundle\Entity\Skill $skills
     * @return User
     */
    public function addSkill(\Application\Sonata\UserBundle\Entity\Skill $skills)
    {
        $this->skills[] = $skills;

        return $this;
    }

    /**
     * Remove skills
     *
     * @param \Application\Sonata\UserBundle\Entity\Skill $skills
     */
    public function removeSkill(\Application\Sonata\UserBundle\Entity\Skill $skills)
    {
        $this->skills->removeElement($skills);
    }

    /**
     * Get skills
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSkills()
    {
        return $this->skills;
    }
}
