<?php
namespace Acme\BudgetTrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="expense")
 * @ORM\Entity(repositoryClass="Acme\BudgetTrackerBundle\Entity\ExpenseRepository")
 */
class Expense
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
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    protected $category;
    
    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     * @Assert\MinLength(
     *     limit=3,
     *     message="The product must have at least {{ limit }} characters."
     * )
     */
    protected $product;
    
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
    protected $date;

    public function getId()
    {
        return $this->id;
    }

    public function setProduct($product)
    {
        $this->product = $product;
    
        return $this;
    }

    public function getProduct()
    {
        return $this->product;
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
}