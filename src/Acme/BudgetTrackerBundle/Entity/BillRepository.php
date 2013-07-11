<?php

namespace Acme\BudgetTrackerBundle\Entity;

use Doctrine\ORM\EntityRepository;

class BillRepository extends EntityRepository
{
    /*public function countCategoriesByName($name, $user)
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
    
    public function countCategoriesByUser($user)
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
    }*/

    public function findBillsByUser($user)
    {
        $q = $this
            ->createQueryBuilder('b')
            ->where('b.user = :user')
            ->setParameter('user', $user)
            ->orderBy('b.date_to_pay_again', 'ASC')
             ->getQuery();
       
        return $q->getResult();
    }
    
    public function findBillsToBePaid($user, $date)
    {
        $q = $this
            ->createQueryBuilder('b')
            ->where('b.user = :user')
            ->andWhere('b.date_to_pay_again <= :date')
            ->setParameter('user', $user)
            ->setParameter('date', $date)
            ->orderBy('b.date_to_pay_again', 'ASC')
             ->getQuery();
       
        return $q->getResult();
    }
}