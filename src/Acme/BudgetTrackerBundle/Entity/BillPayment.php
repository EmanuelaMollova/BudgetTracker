<?php
namespace Acme\BudgetTrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="bill_payment")
 * @ORM\Entity(repositoryClass="Acme\BudgetTrackerBundle\Entity\BillPaymentRepository")
 */
class BillPayment
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="fos_user")
     * @ORM\JoinColumn(name="fos_user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Bill", inversedBy="bill")
     * @ORM\JoinColumn(name="bill_id", referencedColumnName="id")
     */
    protected $bill;
    
    /**
     * @ORM\Column(type="string", nullable = true)
     */
    protected $description;
    
    /**
     * @ORM\Column(type="float")
     */
    protected $price;
    
    /**
     * @ORM\Column(type="datetime")
     */
    protected $date_when_paid;
    
    /**
     * @ORM\Column(type="datetime")
     */
    protected $date_to_pay_again;

    public function getId()
    {
        return $this->id;
    }

    public function setDescription($description = null)
    {
        $this->description = $description;
    
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    
        return $this;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setCategory(\Acme\BudgetTrackerBundle\Entity\Category $category = null)
    {
        $this->category = $category;
    
        return $this;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setUser(\Acme\BudgetTrackerBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set date_when_paid
     *
     * @param \DateTime $dateWhenPaid
     * @return BillPayment
     */
    public function setDateWhenPaid($dateWhenPaid)
    {
        $this->date_when_paid = $dateWhenPaid;
    
        return $this;
    }

    /**
     * Get date_when_paid
     *
     * @return \DateTime 
     */
    public function getDateWhenPaid()
    {
        return $this->date_when_paid;
    }

    /**
     * Set date_to_pay_again
     *
     * @param \DateTime $dateToPayAgain
     * @return BillPayment
     */
    public function setDateToPayAgain($dateToPayAgain)
    {
        $this->date_to_pay_again = $dateToPayAgain;
    
        return $this;
    }

    /**
     * Get date_to_pay_again
     *
     * @return \DateTime 
     */
    public function getDateToPayAgain()
    {
        return $this->date_to_pay_again;
    }

    /**
     * Set bill
     *
     * @param \Acme\BudgetTrackerBundle\Entity\Bill $bill
     * @return BillPayment
     */
    public function setBill(\Acme\BudgetTrackerBundle\Entity\Bill $bill = null)
    {
        $this->bill = $bill;
    
        return $this;
    }

    /**
     * Get bill
     *
     * @return \Acme\BudgetTrackerBundle\Entity\Bill 
     */
    public function getBill()
    {
        return $this->bill;
    }
}