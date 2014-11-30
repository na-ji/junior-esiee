<?php

namespace JuniorEsiee\FinancesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * SupplierInvoice
 *
 * @ORM\Table(name="je_supplier_invoice")
 * @ORM\Entity(repositoryClass="JuniorEsiee\FinancesBundle\Repository\SupplierInvoiceRepository")
 * @UniqueEntity("ref")
 */
class SupplierInvoice
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
     * @ORM\Column(name="ref", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Regex("/^[RF]F\d+$/")
     */
    private $ref;

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
     * @ORM\Column(name="supplier", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $supplier;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="totalAmount", type="integer")
     * @Assert\NotBlank()
     */
    private $totalAmount;

    /**
     * @var integer
     *
     * @ORM\Column(name="taxes_high", type="integer", nullable=true)
     */
    private $taxesHigh;

    /**
     * @var integer
     *
     * @ORM\Column(name="taxes_medium", type="integer", nullable=true)
     */
    private $taxesMedium;

    /**
     * @var integer
     *
     * @ORM\Column(name="taxes_low", type="integer", nullable=true)
     */
    private $taxesLow;

    public function __construct()
    {
        $this->createdAt = new \DateTime;
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
     * @return SupplierInvoice
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return SupplierInvoice
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
     * Set supplier
     *
     * @param string $supplier
     * @return SupplierInvoice
     */
    public function setSupplier($supplier)
    {
        $this->supplier = $supplier;
    
        return $this;
    }

    /**
     * Get supplier
     *
     * @return string 
     */
    public function getSupplier()
    {
        return $this->supplier;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return SupplierInvoice
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
     * Set totalAmount
     *
     * @param integer $totalAmount
     * @return SupplierInvoice
     */
    public function setTotalAmount($totalAmount)
    {
        $this->totalAmount = $this->strToNormalized($totalAmount);
    
        return $this;
    }

    /**
     * Get totalAmount
     *
     * @return integer 
     */
    public function getTotalAmount()
    {
        return $this->normalizedToFloat($this->totalAmount);
    }

    public function getAmount()
    {
        return $this->getTotalAmount() - $this->getTaxesHigh() - $this->getTaxesMedium() - $this->getTaxesLow();
    }

    public function getTaxesAmount()
    {
        return $this->getTaxesHigh() + $this->getTaxesMedium() + $this->getTaxesLow();
    }

    /**
     * Set taxesHigh
     *
     * @param integer $taxesHigh
     * @return SupplierInvoice
     */
    public function setTaxesHigh($taxesHigh)
    {
        $this->taxesHigh = $this->strToNormalized($taxesHigh);
    
        return $this;
    }

    /**
     * Get taxesHigh
     *
     * @return integer 
     */
    public function getTaxesHigh()
    {
        return $this->normalizedToFloat($this->taxesHigh);
    }

    /**
     * Set taxesMedium
     *
     * @param integer $taxesMedium
     * @return SupplierInvoice
     */
    public function setTaxesMedium($taxesMedium)
    {
        $this->taxesMedium = $this->strToNormalized($taxesMedium);
    
        return $this;
    }

    /**
     * Get taxesMedium
     *
     * @return integer 
     */
    public function getTaxesMedium()
    {
        return $this->normalizedToFloat($this->taxesMedium);
    }

    /**
     * Set taxesLow
     *
     * @param integer $taxesLow
     * @return SupplierInvoice
     */
    public function setTaxesLow($taxesLow)
    {
        $this->taxesLow = $this->strToNormalized($taxesLow);
    
        return $this;
    }

    /**
     * Get taxesLow
     *
     * @return integer 
     */
    public function getTaxesLow()
    {
        return $this->normalizedToFloat($this->taxesLow);
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
