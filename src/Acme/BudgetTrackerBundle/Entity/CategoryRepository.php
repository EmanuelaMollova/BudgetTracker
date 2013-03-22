<?php

namespace Acme\BudgetTrackerBundle\Entity;

use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{
    public function countByName($name, $user)
    {
        $q = $this
            ->createQueryBuilder('c')
            ->where('c.name = :name')
            ->andWhere('c.user = :user')
            ->setParameter('name', $name)
            ->setParameter('user', $user)
             ->getQuery();
        
        $this->countRowsQ = $q->getScalarResult();
        
        return $this->countRowsQ;
    }
}