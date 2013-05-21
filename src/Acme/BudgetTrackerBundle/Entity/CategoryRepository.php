<?php

namespace Acme\BudgetTrackerBundle\Entity;

use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{
    public function countByNameAndUser($name, $user)
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
    
    public function countByUser($user)
    {
        $q = $this
            ->createQueryBuilder('c')
            ->select('COUNT(c.id)') 
            ->where('c.user = :user')
            ->setParameter('user', $user)
             ->getQuery();
       
        return $q->getSingleScalarResult();
    }
    
    public function findByUser($user)
    {
        $q = $this
            ->createQueryBuilder('c')
            ->where('c.user = :user')
            ->setParameter('user', $user)
             ->getQuery();
       
        return $q->getResult();
    }
}