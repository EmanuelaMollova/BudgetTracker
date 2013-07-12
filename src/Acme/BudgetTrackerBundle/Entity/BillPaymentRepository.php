<?php

namespace Acme\BudgetTrackerBundle\Entity;

use Doctrine\ORM\EntityRepository;

class BillPaymentRepository extends EntityRepository
{
    public function findPaymentsByBill($user, $bill)
    {
        $q = $this
            ->createQueryBuilder('p') 
            ->where('p.user = :user')
            ->andWhere('p.bill = :bill')
            ->setParameter('user', $user)
            ->setParameter('bill', $bill)
             ->getQuery();
       
        return $q->getResult();
    }
    
    public function findSumOfPaymentsByBill($user, $bill)
    {
        $q = $this->createQueryBuilder('p')
            ->add('select', 'SUM(p.price)')
            ->where('p.user = :user') 
            ->andWhere('p.bill = :bill')
            ->setParameter('user', $user)
             ->setParameter('bill', $bill)
            ->getQuery();

        return $q->getSingleScalarResult();
    }
    
    public function findPaymentsByMonth($month, $year, $user)
    {
        $emConfig = $this->getEntityManager()->getConfiguration();
        $emConfig->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
        $emConfig->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');
        
        $q = $this
            ->createQueryBuilder('p')
            ->where('MONTH(p.date_when_paid) = :month') 
            ->andWhere('YEAR(p.date_when_paid) = :year')
            ->andWhere('p.user = :user')
            ->orderBy('p.bill', 'ASC')
            ->setParameter('month', $month)
            ->setParameter('year', $year)
            ->setParameter('user', $user)          
            ->getQuery();
        
        return $q->getResult();
    } 
    
    public function findSumOfPaymentsByMonth($month, $year, $user)
    {
        $emConfig = $this->getEntityManager()->getConfiguration();
        $emConfig->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
        $emConfig->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');
        
        $q = $this->createQueryBuilder('p')
            ->add('select', 'SUM(p.price)')
            ->where('MONTH(p.date_when_paid) = :month')
            ->andWhere('YEAR(p.date_when_paid) = :year')
            ->andWhere('p.user = :user') 
            ->setParameter('month', $month)
            ->setParameter('year', $year)
            ->setParameter('user', $user)
            ->getQuery();

        return $q->getSingleScalarResult();
    }
    
    public function findPaymentsBetweenDates($user, $from_date, $to_date)
    {
        $q = $this
            ->createQueryBuilder('p')
            ->where('p.date_when_paid >= :from_date')
            ->andWhere('p.date_when_paid < :to_date')
            ->andWhere('p.user = :user')
            ->orderBy('p.bill', 'ASC')
            ->setParameter('from_date', $from_date)
            ->setParameter('to_date', $to_date)
            ->setParameter('user', $user)
            ->getQuery();
        
        return $q->getResult();
    }

    public function findSumOfPaymentsBetweenDates($from_date, $to_date, $user)
    {
        $q = $this
            ->createQueryBuilder('p')
            ->add('select', 'SUM(p.price)')
            ->where('p.date_when_paid >= :from_date')
            ->andWhere('p.date_when_paid < :to_date')
            ->andWhere('p.user = :user')
            ->setParameter('from_date', $from_date)
            ->setParameter('to_date', $to_date)
            ->setParameter('user', $user)
             ->getQuery();
        
        return $q->getSingleScalarResult();
    }
}