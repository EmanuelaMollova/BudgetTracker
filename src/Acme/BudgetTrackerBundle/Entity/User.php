<?php

namespace Acme\BudgetTrackerBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="category")
     */
    protected $categories;
    
    /**
     * @ORM\OneToMany(targetEntity="Expense", mappedBy="expense")
     */
    protected $expenses;

    public function __construct()
    {
        parent::__construct();
        
        $this->categories = new ArrayCollection();
        $this->expenses = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function addCategory(\Acme\BudgetTrackerBundle\Entity\Category $categories)
    {
        $this->categories[] = $categories;
    
        return $this;
    }

    public function removeCategory(\Acme\BudgetTrackerBundle\Entity\Category $categories)
    {
        $this->categories->removeElement($categories);
    }

    public function getCategories()
    {
        return $this->categories;
    }

    public function addExpense(\Acme\BudgetTrackerBundle\Entity\Expense $expenses)
    {
        $this->expenses[] = $expenses;
    
        return $this;
    }

    public function removeExpense(\Acme\BudgetTrackerBundle\Entity\Expense $expenses)
    {
        $this->expenses->removeElement($expenses);
    }

    public function getExpenses()
    {
        return $this->expenses;
    }
}