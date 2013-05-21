<?php
namespace Acme\BudgetTrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="month")
 * @ORM\Entity(repositoryClass="Acme\BudgetTrackerBundle\Entity\MonthRepository")
 * @UniqueEntity(fields={"name", "user"})
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
     * @Assert\NotBlank()
     */
    protected $name;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="fos_user")
     * @ORM\JoinColumn(name="fos_user_id", referencedColumnName="id")
     */
    protected $user;
    
    /**
     * @ORM\Column(type="float")
     */
    protected $budget;

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

    public function setBudget($budget)
    {
        $this->budget = $budget;
    
        return $this;
    }

    public function getBudget()
    {
        return $this->budget;
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
}