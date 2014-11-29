<?php

namespace JuniorEsiee\BusinessBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Project
 */
class Project
{
    const STATE_WAITING_INFORMATION = 'state_waiting_information';
    const STATE_WAITING_STUDENT     = 'state_waiting_student';
    const STATE_ABORTED             = 'state_aborted';
    const STATE_CLOSED              = 'state_closed';

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $clientLastName;

    /**
     * @var string
     */
    private $clientFirstName;

    /**
     * @var string
     */
    private $clientCompany;

    /**
     * @var string
     */
    private $clientPhone;

    /**
     * @var string
     */
    private $clientEmail;

    /**
     * @var \DateTime
     */
    private $depositDate;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    /**
     * @var \DateTime
     */
    private $delay;

    /**
     * @var string
     */
    private $state = Project::STATE_WAITING_INFORMATION;

    /**
     * @var \Application\Sonata\UserBundle\Entity\User
     */
    private $commercial;

    /**
     * @var \Application\Sonata\UserBundle\Entity\User
     */
    private $rbu;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $students;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $skills;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $skillCategories;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->students = new \Doctrine\Common\Collections\ArrayCollection();
        $this->skills = new \Doctrine\Common\Collections\ArrayCollection();
        $this->skillCategories = new \Doctrine\Common\Collections\ArrayCollection();
        $this->depositDate = new \DateTime();
        $this->delay = new \DateTime();
    }

    public function __toString()
    {
        return ''.$this->title;
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
     * Set clientLastName
     *
     * @param string $clientLastName
     * @return Project
     */
    public function setClientLastName($clientLastName)
    {
        $this->clientLastName = $clientLastName;

        return $this;
    }

    /**
     * Get clientLastName
     *
     * @return string 
     */
    public function getClientLastName()
    {
        return $this->clientLastName;
    }

    /**
     * Set clientFirstName
     *
     * @param string $clientFirstName
     * @return Project
     */
    public function setClientFirstName($clientFirstName)
    {
        $this->clientFirstName = $clientFirstName;

        return $this;
    }

    /**
     * Get clientFirstName
     *
     * @return string 
     */
    public function getClientFirstName()
    {
        return $this->clientFirstName;
    }

    /**
     * Set clientCompany
     *
     * @param string $clientCompany
     * @return Project
     */
    public function setClientCompany($clientCompany)
    {
        $this->clientCompany = $clientCompany;

        return $this;
    }

    /**
     * Get clientCompany
     *
     * @return string 
     */
    public function getClientCompany()
    {
        return $this->clientCompany;
    }

    /**
     * Set clientPhone
     *
     * @param string $clientPhone
     * @return Project
     */
    public function setClientPhone($clientPhone)
    {
        $this->clientPhone = $clientPhone;

        return $this;
    }

    /**
     * Get clientPhone
     *
     * @return string 
     */
    public function getClientPhone()
    {
        return $this->clientPhone;
    }

    /**
     * Set clientEmail
     *
     * @param string $clientEmail
     * @return Project
     */
    public function setClientEmail($clientEmail)
    {
        $this->clientEmail = $clientEmail;

        return $this;
    }

    /**
     * Get clientEmail
     *
     * @return string 
     */
    public function getClientEmail()
    {
        return $this->clientEmail;
    }

    /**
     * Set depositDate
     *
     * @param \DateTime $depositDate
     * @return Project
     */
    public function setDepositDate($depositDate)
    {
        $this->depositDate = $depositDate;

        return $this;
    }

    /**
     * Get depositDate
     *
     * @return \DateTime 
     */
    public function getDepositDate()
    {
        return $this->depositDate;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Project
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Project
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set delay
     *
     * @param \DateTime $delay
     * @return Project
     */
    public function setDelay($delay)
    {
        $this->delay = $delay;

        return $this;
    }

    /**
     * Get delay
     *
     * @return \DateTime 
     */
    public function getDelay()
    {
        return $this->delay;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return Project
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Get valid states
     *
     * @return array
     */
    public static function getStates()
    {
        return array(Project::STATE_WAITING_INFORMATION, Project::STATE_WAITING_STUDENT, Project::STATE_ABORTED, Project::STATE_CLOSED);
    }

    /**
     * Set commercial
     *
     * @param \Application\Sonata\UserBundle\Entity\User $commercial
     * @return Project
     */
    public function setCommercial(\Application\Sonata\UserBundle\Entity\User $commercial = null)
    {
        $this->commercial = $commercial;

        return $this;
    }

    /**
     * Get commercial
     *
     * @return \Application\Sonata\UserBundle\Entity\User 
     */
    public function getCommercial()
    {
        return $this->commercial;
    }

    /**
     * Set rbu
     *
     * @param \Application\Sonata\UserBundle\Entity\User $rbu
     * @return Project
     */
    public function setRbu(\Application\Sonata\UserBundle\Entity\User $rbu = null)
    {
        $this->rbu = $rbu;

        return $this;
    }

    /**
     * Get rbu
     *
     * @return \Application\Sonata\UserBundle\Entity\User 
     */
    public function getRbu()
    {
        return $this->rbu;
    }

    /**
     * Add students
     *
     * @param \Application\Sonata\UserBundle\Entity\User $students
     * @return Project
     */
    public function addStudent(\Application\Sonata\UserBundle\Entity\User $students)
    {
        $this->students[] = $students;

        return $this;
    }

    /**
     * Remove students
     *
     * @param \Application\Sonata\UserBundle\Entity\User $students
     */
    public function removeStudent(\Application\Sonata\UserBundle\Entity\User $students)
    {
        $this->students->removeElement($students);
    }

    /**
     * Get students
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getStudents()
    {
        return $this->students;
    }

    /**
     * Add skills
     *
     * @param \Application\Sonata\UserBundle\Entity\Skill $skills
     * @return Project
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

    /**
     * Add skillCategories
     *
     * @param \Application\Sonata\UserBundle\Entity\SkillCategory $skillCategories
     * @return Project
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
}
