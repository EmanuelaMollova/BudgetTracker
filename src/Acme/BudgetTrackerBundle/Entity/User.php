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

    public function __construct()
    {
        parent::__construct();
        
        $this->categories = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function addCategorie(\Acme\BudgetTrackerBundle\Entity\Category $categories)
    {
        $this->categories[] = $categories;
    
        return $this;
    }

    public function removeCategorie(\Acme\BudgetTrackerBundle\Entity\Category $categories)
    {
        $this->categories->removeElement($categories);
    }

    public function getCategories()
    {
        return $this->categories;
    }
}