<?php
namespace Acme\BudgetTrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="Acme\BudgetTrackerBundle\Entity\CategoryRepository")
 * @UniqueEntity(fields={"name", "user"})
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\OneToMany(targetEntity="Expense", mappedBy="expense")
     */
    protected $expenses;

    public function __construct()
    {
        $this->expenses = new ArrayCollection();
    }

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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="fos_user")
     * @ORM\JoinColumn(name="fos_user_id", referencedColumnName="id")
     */
    protected $user;

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