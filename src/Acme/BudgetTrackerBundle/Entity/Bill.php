<?php
namespace Acme\BudgetTrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="bill")
 * @ORM\Entity(repositoryClass="Acme\BudgetTrackerBundle\Entity\BillRepository")
 */
class Bill
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\OneToMany(targetEntity="BillPayment", mappedBy="billpayment")
     */
    protected $payments;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     * @Assert\MinLength(
     *     limit=3,
     *     message="The name must have at least {{ limit }} characters."
     * )
     */
    protected $name;
    
    /**
     * @ORM\Column(type="datetime", nullable = true)
     */
    protected $date_to_pay_again;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="fos_user")
     * @ORM\JoinColumn(name="fos_user_id", referencedColumnName="id")
     */
    protected $user;
    
        public function __construct()
    {
        $this->payments = new ArrayCollection();
        $this->date_to_pay_again = null;
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    public function getName()
    {
        return $this->name;
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
     * Add payments
     *
     * @param \Acme\BudgetTrackerBundle\Entity\BillPayment $payments
     * @return Bill
     */
    public function addPayment(\Acme\BudgetTrackerBundle\Entity\BillPayment $payments)
    {
        $this->payments[] = $payments;
    
        return $this;
    }

    /**
     * Remove payments
     *
     * @param \Acme\BudgetTrackerBundle\Entity\BillPayment $payments
     */
    public function removePayment(\Acme\BudgetTrackerBundle\Entity\BillPayment $payments)
    {
        $this->payments->removeElement($payments);
    }

    /**
     * Get payments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPayments()
    {
        return $this->payments;
    }

    /**
     * Set date_to_pay_again
     *
     * @param \DateTime $dateToPayAgain
     * @return Bill
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
}