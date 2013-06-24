<?php

namespace Acme\BudgetTrackerBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ExpenseRepository extends EntityRepository
{
    public function findExpensesByMonth($month, $year, $user, $category, $returned = 0)
    {
        $emConfig = $this->getEntityManager()->getConfiguration();
        $emConfig->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
        $emConfig->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');
        
        $q = $this
            ->createQueryBuilder('e')
            ->where('MONTH(e.date) = :month') 
            ->andWhere('YEAR(e.date) = :year')
            ->andWhere('e.user = :user')
            ->andWhere('e.category <> :category')
            ->andWhere('e.returned = :returned')
            ->orderBy('e.category', 'ASC')
            ->setParameter('month', $month)
            ->setParameter('year', $year)
            ->setParameter('user', $user) 
            ->setParameter('category', $category)
            ->setParameter('returned', $returned)           
            ->getQuery();
        
        return $q->getResult();
    } 
    
    public function findSumOfExpensesByMonth($month, $year, $user, $category, $returned = 0)
    {
        $emConfig = $this->getEntityManager()->getConfiguration();
        $emConfig->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
        $emConfig->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');
        
        $q = $this->createQueryBuilder('e')
            ->add('select', 'SUM(e.price)')
            ->where('MONTH(e.date) = :month')
            ->andWhere('YEAR(e.date) = :year')
            ->andWhere('e.user = :user') 
            ->andWhere('e.category <> :category')
            ->andWhere('e.returned = :returned') 
            ->setParameter('month', $month)
            ->setParameter('year', $year)
            ->setParameter('user', $user)
             ->setParameter('category', $category)
            ->setParameter('returned', $returned)
            ->getQuery();

        return $q->getSingleScalarResult();
    }

    public function findExpensesBetweenDates($from_date, $to_date, $user, $category, $returned = 0)
    {
        $q = $this
            ->createQueryBuilder('e')
            ->where('e.date >= :from_date')
            ->andWhere('e.date < :to_date')
            ->andWhere('e.user = :user')
            ->andWhere('e.category <> :category')
            ->andWhere('e.returned = :returned')
            ->orderBy('e.category', 'ASC')
            ->setParameter('from_date', $from_date)
            ->setParameter('to_date', $to_date)
            ->setParameter('user', $user)
            ->setParameter('category', $category)
            ->setParameter('returned', $returned)
            ->getQuery();
        
        return $q->getResult();
    }

    public function findSumOfExpensesBetweenDates($from_date, $to_date, $user, $category, $returned = 0)
    {
        $q = $this
            ->createQueryBuilder('e')
            ->add('select', 'SUM(e.price)')
            ->where('e.date >= :from_date')
            ->andWhere('e.date < :to_date')
            ->andWhere('e.user = :user')
            ->andWhere('e.category <> :category')
            ->andWhere('e.returned = :returned') 
            ->setParameter('from_date', $from_date)
            ->setParameter('to_date', $to_date)
            ->setParameter('user', $user)
            ->setParameter('category', $category)
            ->setParameter('returned', $returned)
             ->getQuery();
        
        return $q->getSingleScalarResult();
    }
  
    public function findExpensesByCategoriesAndDates($start_date, $end_date, $q, $user, $category, $returned = 0)
    {
         $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT e FROM AcmeBudgetTrackerBundle:Expense e WHERE e.user = ?1 AND e.date >= ?2 AND e.date < ?3 AND e.category <> ?4 AND e.returned = ?5'.$q. 'ORDER BY e.category, e.date');
        $query->setParameter(1, $user);
        $query->setParameter(2, $start_date);
        $query->setParameter(3, $end_date);
        $query->setParameter(4, $category);
        $query->setParameter(5, $returned);
        
        return $query->getResult();
    }
    
    public function findExpensesByCategory($user, $category, $returned = 0)
    {
        $q = $this
            ->createQueryBuilder('e') 
            ->where('e.user = :user')
            ->andWhere('e.category = :category')
            ->andWhere('e.returned = :returned')
            ->setParameter('user', $user)
            ->setParameter('category', $category)
            ->setParameter('returned', $returned)
             ->getQuery();
       
        return $q->getResult();
    }
    
    public function findSumOfExpensesByMonthAndCategory($month, $year, $user, $category, $returned = 0)
    {
        $emConfig = $this->getEntityManager()->getConfiguration();
        $emConfig->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
        $emConfig->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');
        
        $q = $this->createQueryBuilder('e')
            ->add('select', 'SUM(e.price)')
            ->where('MONTH(e.date) = :month')
            ->andWhere('YEAR(e.date) = :year')
            ->andWhere('e.user = :user') 
            ->andWhere('e.category = :category')
            ->andWhere('e.returned = :returned') 
            ->setParameter('month', $month)
            ->setParameter('year', $year)
            ->setParameter('user', $user)
             ->setParameter('category', $category)
            ->setParameter('returned', $returned)
            ->getQuery();

        return $q->getSingleScalarResult();
    }
}