<?php

namespace Acme\BudgetTrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityRepository;

class MonthRepository extends EntityRepository
{
    public function countMonthsByNameAndUser($name, $user)
    {
        $q = $this
            ->createQueryBuilder('m')
            ->select('COUNT(m.id)') 
            ->where('m.name = :name')
            ->andWhere('m.user = :user')
            ->setParameter('name', $name)
            ->setParameter('user', $user)
             ->getQuery();
        
        return $q->getSingleScalarResult();
    }

    public function findMonthsByUser($user)
    {
        $q = $this
            ->createQueryBuilder('m')
            ->where('m.user = :user')
            ->orderBy('m.date', 'ASC')
            ->setParameter('user', $user)
             ->getQuery();
       
        return $q->getResult();
    }

    public function findMonthByUser($month, $year, $user)
    {
        $emConfig = $this->getEntityManager()->getConfiguration();
        $emConfig->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
        $emConfig->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');
        
        $q = $this
            ->createQueryBuilder('m')
            ->where('MONTH(m.date) = :month')
            ->andWhere('YEAR(m.date) = :year')
            ->andWhere('m.user = :user')      
            ->setParameter('month', $month)
            ->setParameter('year', $year)
            ->setParameter('user', $user)     
             ->getQuery();
       
        return $q->getResult(); 
    }
}