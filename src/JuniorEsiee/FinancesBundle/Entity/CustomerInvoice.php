<?php

namespace JuniorEsiee\FinancesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContextInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * CustomerInvoice
 *
 * @ORM\Table(name="je_customer_invoice")
 * @ORM\Entity(repositoryClass="JuniorEsiee\FinancesBundle\Repository\CustomerInvoiceRepository")
 * @Assert\Callback(methods={"checkDates"})
 * @UniqueEntity("fc")
 */
class CustomerInvoice
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
     * @ORM\Column(name="fc", type="string", length=40, unique=true)
     * @Assert\NotBlank()
     * @Assert\Regex("/^FC\d+$/")
     */
    private $fc;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="issued_at", type="datetime")
     * @Assert\NotBlank()
     */
    private $issuedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="due_date", type="datetime")
     * @Assert\NotBlank()
     */
    private $dueDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="paid_at", type="datetime", nullable=true)
     */
    private $paidAt;

    /**
     * @var string
     *
     * @ORM\Column(name="client", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $client;

    /**
     * @var integer
     *
     * @ORM\Column(name="percentage", type="integer")
     * @Assert\NotBlank()
     */
    private $percentage;

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
     * @ORM\Column(name="taxes", type="integer")
     * @Assert\NotBlank()
     */
    private $taxes;

    /**
     * @var boolean
     *
     * @ORM\Column(name="paid", type="boolean")
     */
    private $paid;

    /**
     * @ORM\OneToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"all"})
     */
    private $file;

    public function __construct(Variable $defaultTaxes = null)
    {
        $this->paid = false;
        $this->fc = 'FC';
        $this->issuedAt = new \DateTime;
        $this->dueDate = (new \DateTime)->add(new \DateInterval('P1M'));

        if($defaultTaxes !== null)
            $this->setTaxes($defaultTaxes->getValue());
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
     * @return CustomerInvoice
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
     * Set fc
     *
     * @param string $fc
     * @return CustomerInvoice
     */
    public function setFc($fc)
    {
        $this->fc = $fc;
    
        return $this;
    }

    /**
     * Get fc
     *
     * @return string 
     */
    public function getFc()
    {
        return $this->fc;
    }

    /**
     * Set issuedAt
     *
     * @param \DateTime $issuedAt
     * @return CustomerInvoice
     */
    public function setIssuedAt($issuedAt)
    {
        $this->issuedAt = $issuedAt;
    
        return $this;
    }

    /**
     * Get issuedAt
     *
     * @return \DateTime 
     */
    public function getIssuedAt()
    {
        return $this->issuedAt;
    }

    /**
     * Set dueDate
     *
     * @param \DateTime $dueDate
     * @return CustomerInvoice
     */
    public function setDueDate($dueDate)
    {
        $this->dueDate = $dueDate;
    
        return $this;
    }

    /**
     * Get dueDate
     *
     * @return \DateTime 
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * @param \DateTime $paidAt
     */
    public function setPaidAt($paidAt)
    {
        $this->paidAt = $paidAt;
    }

    /**
     * @return \DateTime
     */
    public function getPaidAt()
    {
        return $this->paidAt;
    }

    /**
     * Set client
     *
     * @param string $client
     * @return CustomerInvoice
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
     * Set percentage
     *
     * @param integer $percentage
     * @return CustomerInvoice
     */
    public function setPercentage($percentage)
    {
        $this->percentage = $this->strToNormalized($percentage);
    
        return $this;
    }

    /**
     * Get percentage
     *
     * @return integer 
     */
    public function getPercentage()
    {
        return $this->normalizedToFloat($this->percentage);
    }

    /**
     * Set amount
     *
     * @param integer $amount
     * @return CustomerInvoice
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

    /**
     * Set taxes
     *
     * @param integer $taxes
     * @return CustomerInvoice
     */
    public function setTaxes($taxes)
    {
        $this->taxes = $this->strToNormalized($taxes);
    
        return $this;
    }

    /**
     * Get taxes
     *
     * @return integer 
     */
    public function getTaxes()
    {
        return $this->normalizedToFloat($this->taxes);
    }

    /**
     * Get taxes amount
     *
     * @return integer
     */
    public function getTaxesAmount()
    {
        return $this->getAmount() * $this->getTaxes() / 100;
    }

    /**
     * Get taxes amount
     *
     * @return integer
     */
    public function getTotalAmount()
    {
        return $this->getAmount() + $this->getTaxesAmount();
    }

    /**
     * Set paid
     *
     * @param boolean $paid
     * @return CustomerInvoice
     */
    public function setPaid($paid)
    {
        $this->paid = $paid;
    
        return $this;
    }

    /**
     * Get paid
     *
     * @return boolean 
     */
    public function getPaid()
    {
        return $this->paid;
    }

    /**
     * @param mixed $file
     * @return CustomerInvoice
     */
    public function setFile($file)
    {
        $this->file = $file;
    
        return $this;
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

    public function checkDates(ExecutionContextInterface $context)
    {
        if($this->dueDate !== null){
            if($this->issuedAt->diff($this->dueDate)->invert === 1)
                $context->addViolationAt('dueDate', "La date d'échéance ne peut pas être avant la date d'émmission");
        }
    }
}
