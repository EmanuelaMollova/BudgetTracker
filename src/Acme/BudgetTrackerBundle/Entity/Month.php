<?php
namespace Acme\BudgetTrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="month")
 * @ORM\Entity(repositoryClass="Acme\BudgetTrackerBundle\Entity\MonthRepository")
 */
class Month
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;
    
    /**
     * @ORM\Column(type="datetime")
     */
    protected $date;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="fos_user")
     * @ORM\JoinColumn(name="fos_user_id", referencedColumnName="id")
     */
    protected $user;
    
    /**
     * @ORM\Column(type="float")
     */
    protected $budget;
    
    /**
     * @ORM\Column(type="float")
     */
    protected $transfered;
    
    public function __construct()
    {
        $this->transfered = 0;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $this->getDate()->format('F').' '.$this->getDate()->format('Y');
    
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setBudget($budget)
    {
        $this->budget = $budget;
    
        return $this;
    }

    public function getBudget()
    {
        return $this->budget;
    }
    
        public function setTransfered($transfered)
    {
        $this->transfered = $transfered;
    
        return $this;
    }

    public function getTransfered()
    {
        return $this->transfered;
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

    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }
}