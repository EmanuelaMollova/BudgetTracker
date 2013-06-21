<?php

namespace Acme\BudgetTrackerBundle\Entity;

use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{
    //used
    public function countByName($name, $user)
    {
        $q = $this
            ->createQueryBuilder('c')
            ->select('COUNT(c.id)') 
            ->where('c.name = :name')
            ->andWhere('c.user = :user')
            ->setParameter('name', $name)
            ->setParameter('user', $user)
             ->getQuery();
        
        return $q->getSingleScalarResult();
    }
    
    //used
    public function countByUser($user)
    {
        $q = $this
            ->createQueryBuilder('c')
            ->select('COUNT(c.id)') 
            ->where('c.user = :user')
            ->andWhere('c.name <> :loans')
            ->andWhere('c.name <> :debts')
            ->setParameter('user', $user)
            ->setParameter('loans', 'Loans')
            ->setParameter('debts', 'Debts')
             ->getQuery();
       
        return $q->getSingleScalarResult();
    }
    

    
    public function findByUser($user)
    {
        $q = $this
            ->createQueryBuilder('c')
            ->where('c.user = :user')
             ->andWhere('c.name <> :loans')
            ->andWhere('c.name <> :debts')
            ->setParameter('user', $user)
                ->setParameter('loans', 'Loans')
            ->setParameter('debts', 'Debts')
             ->getQuery();
       
        return $q->getResult();
    }
    
    public function findByNameUser($user, $name)
    {
        $q = $this
            ->createQueryBuilder('c')
            ->where('c.user = :user')
             ->andWhere('c.name = :name')
            ->setParameter('user', $user)
                ->setParameter('name', $name)
             ->getQuery();
       
        return $q->getResult();
    }
}