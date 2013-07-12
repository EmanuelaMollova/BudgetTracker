<?php

namespace Acme\BudgetTrackerBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;

class BillRepository extends EntityRepository
{
    public function countBillsByName($name, $user)
    {
        $q = $this
            ->createQueryBuilder('b')
            ->select('COUNT(b.id)') 
            ->where('b.name = :name')
            ->andWhere('b.user = :user')
            ->setParameter('name', $name)
            ->setParameter('user', $user)
             ->getQuery();
        
        return $q->getSingleScalarResult();
    }

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