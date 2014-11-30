<?php

namespace JuniorEsiee\FinancesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * PaymentSlip
 *
 * @ORM\Table(name="je_payment_slip")
 * @ORM\Entity(repositoryClass="JuniorEsiee\FinancesBundle\Repository\PaymentSlipRepository")
 * @UniqueEntity("bv")
 */
class PaymentSlip
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="ref", type="string", length=40)
     * @Assert\NotBlank()
     * @Assert\Regex("/^\d+$/")
     */
    private $ref;

    /**
     * @var string
     *
     * @ORM\Column(name="bv", type="string", length=40, unique=true)
     * @Assert\NotBlank()
     * @Assert\Regex("/^BV\d+$/")
     */
    private $bv;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @Assert\NotBlank()
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="client", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $client;

    /**
     * @var string
     *
     * @ORM\Column(name="student", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $student;

    /**
     * @var integer
     *
     * @ORM\Column(name="amount", type="integer")
     * @Assert\NotBlank()
     */
    private $amount;

    /**
     * @var integer
     *
     * @ORM\Column(name="number_of_days", type="integer")
     * @Assert\NotBlank()
     */
    private $numberOfDays;

    /**
     * @var integer
     *
     * @ORM\Column(name="smic", type="integer")
     * @Assert\NotBlank()
     */
    private $smic;

    /**
     * @var integer
     *
     * @ORM\Column(name="urssaf_1_1", type="integer")
     * @Assert\NotBlank()
     */
    private $urssaf_1_1;

    /**
     * @var integer
     *
     * @ORM\Column(name="urssaf_1_2", type="integer")
     * @Assert\NotBlank()
     */
    private $urssaf_1_2;

    /**
     * @var integer
     *
     * @ORM\Column(name="urssaf_2_1", type="integer")
     * @Assert\NotBlank()
     */
    private $urssaf_2_1;

    /**
     * @var integer
     *
     * @ORM\Column(name="urssaf_2_2", type="integer")
     * @Assert\NotBlank()
     */
    private $urssaf_2_2;

    /**
     * @var integer
     *
     * @ORM\Column(name="urssaf_2_3", type="integer")
     * @Assert\NotBlank()
     */
    private $urssaf_2_3;

    /**
     * @var integer
     *
     * @ORM\Column(name="urssaf_2_4", type="integer")
     * @Assert\NotBlank()
     */
    private $urssaf_2_4;

    /**
     * @ORM\OneToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"all"})
     */
    private $file;

    public function __construct(Variable $smic = null, Variable $urssaf_1_1 = null, Variable $urssaf_1_2 = null, Variable $urssaf_2_1 = null, Variable $urssaf_2_2 = null, Variable $urssaf_2_3 = null, Variable $urssaf_2_4 = null)
    {
        $this->bv = 'BV';
        $this->createdAt = new \DateTime;

        if($smic !== null)
            $this->setSmic($smic->getValue());
        if($urssaf_1_1 !== null)
            $this->setUrssaf11($urssaf_1_1->getValue());
        if($urssaf_1_2 !== null)
            $this->setUrssaf12($urssaf_1_2->getValue());
        if($urssaf_2_1 !== null)
            $this->setUrssaf21($urssaf_2_1->getValue());
        if($urssaf_2_2 !== null)
            $this->setUrssaf22($urssaf_2_2->getValue());
        if($urssaf_2_3 !== null)
            $this->setUrssaf23($urssaf_2_3->getValue());
        if($urssaf_2_4 !== null)
            $this->setUrssaf24($urssaf_2_4->getValue());
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
     * Set ref
     *
     * @param string $ref
     * @return PaymentSlip
     */
    public function setRef($ref)
    {
        $this->ref = $ref;
    
        return $this;
    }

    /**
     * Get ref
     *
     * @return string 
     */
    public function getRef()
    {
        return $this->ref;
    }

    /**
     * Set bv
     *
     * @param string $bv
     * @return PaymentSlip
     */
    public function setBv($bv)
    {
        $this->bv = $bv;
    
        return $this;
    }

    /**
     * Get bv
     *
     * @return string 
     */
    public function getBv()
    {
        return $this->bv;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return PaymentSlip
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set client
     *
     * @param string $client
     * @return PaymentSlip
     */
    public function setClient($client)
    {
        $this->client = $client;
    
        return $this;
    }

    /**
     * Get client
     *
     * @return string 
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set student
     *
     * @param string $student
     * @return PaymentSlip
     */
    public function setStudent($student)
    {
        $this->student = $student;
    
        return $this;
    }

    /**
     * Get student
     *
     * @return string 
     */
    public function getStudent()
    {
        return $this->student;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     * @return PaymentSlip
     */
    public function setAmount($amount)
    {
        $this->amount = $this->strToNormalized($amount);
    
        return $this;
    }

    /**
     * Get amount
     *
     * @return integer 
     */
    public function getAmount()
    {
        return $this->normalizedToFloat($this->amount);
    }

    public function getUrssaf()
    {
        return $this->numberOfDays * 4 * $this->getSmic() * ($this->getUrssaf21() + $this->getUrssaf22() + $this->getUrssaf23() + $this->getUrssaf24())/100 + $this->getAmount() * ($this->getUrssaf11() + $this->getUrssaf12())/100;
    }

    public function getTotalAmount()
    {
        return $this->getAmount() - $this->getUrssaf();
    }

    /**
     * Set numberOfDays
     *
     * @param integer $numberOfDays
     * @return PaymentSlip
     */
    public function setNumberOfDays($numberOfDays)
    {
        $this->numberOfDays = $numberOfDays;
    
        return $this;
    }

    /**
     * Get numberOfDays
     *
     * @return integer 
     */
    public function getNumberOfDays()
    {
        return $this->numberOfDays;
    }

    /**
     * @param int $smic
     */
    public function setSmic($smic)
    {
        $this->smic = $this->strToNormalized($smic);
    }

    /**
     * @return int
     */
    public function getSmic()
    {
        return $this->normalizedToFloat($this->smic);
    }

    /**
     * @param int $urssaf_1_1
     */
    public function setUrssaf11($urssaf_1_1)
    {
        $this->urssaf_1_1 = $this->strToNormalized($urssaf_1_1);
    }

    /**
     * @return int
     */
    public function getUrssaf11()
    {
        return $this->normalizedToFloat($this->urssaf_1_1);
    }

    /**
     * @param int $urssaf_1_2
     */
    public function setUrssaf12($urssaf_1_2)
    {
        $this->urssaf_1_2 = $this->strToNormalized($urssaf_1_2);
    }

    /**
     * @return int
     */
    public function getUrssaf12()
    {
        return $this->normalizedToFloat($this->urssaf_1_2);
    }

    /**
     * @param int $urssaf_2_1
     */
    public function setUrssaf21($urssaf_2_1)
    {
        $this->urssaf_2_1 = $this->strToNormalized($urssaf_2_1);
    }

    /**
     * @return int
     */
    public function getUrssaf21()
    {
        return $this->normalizedToFloat($this->urssaf_2_1);
    }

    /**
     * @param int $urssaf_2_2
     */
    public function setUrssaf22($urssaf_2_2)
    {
        $this->urssaf_2_2 = $this->strToNormalized($urssaf_2_2);
    }

    /**
     * @return int
     */
    public function getUrssaf22()
    {
        return $this->normalizedToFloat($this->urssaf_2_2);
    }

    /**
     * @param int $urssaf_2_3
     */
    public function setUrssaf23($urssaf_2_3)
    {
        $this->urssaf_2_3 = $this->strToNormalized($urssaf_2_3);
    }

    /**
     * @return int
     */
    public function getUrssaf23()
    {
        return $this->normalizedToFloat($this->urssaf_2_3);
    }

    /**
     * @param int $urssaf_2_4
     */
    public function setUrssaf24($urssaf_2_4)
    {
        $this->urssaf_2_4 = $this->strToNormalized($urssaf_2_4);
    }

    /**
     * @return int
     */
    public function getUrssaf24()
    {
        return $this->normalizedToFloat($this->urssaf_2_4);
    }

    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    public function hasFile()
    {
        return $this->file !== null;
    }

    private function strToNormalized($str)
    {
        $str = str_replace(',', '.', $str);
        $str = str_replace(' ', '', $str);
        return intval(100 * floatval($str));
    }

    private function normalizedToFloat($int)
    {
        if($int === null) return null;
        return floatval($int / 100);
    }
}
