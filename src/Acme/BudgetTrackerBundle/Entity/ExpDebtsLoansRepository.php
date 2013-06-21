<?php

namespace Acme\BudgetTrackerBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ExpenseRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ExpDebtsLoansRepository extends EntityRepository
{
    //used
    public function findExpensesForDate($fromDate, $toDate, $user)
    {
        $q = $this
            ->createQueryBuilder('e')
            ->where('e.date >= :fromDate')
            ->andWhere('e.date < :toDate')
            ->andWhere('e.user = :user')
            ->setParameter('fromDate', $fromDate)
            ->setParameter('toDate', $toDate)
            ->setParameter('user', $user)
             ->getQuery();
        
        return $q->getResult();
    }

//    public function findExpensesForMonthAndCat($user, $month, $category)
//    {
//        $q = $this
//            ->createQueryBuilder('e')
//            ->where('e.date LIKE :month')
//            ->andWhere('e.user = :user')
//            ->andWhere('e.category = :category')
//            ->setParameter('month', "%$month")
//            ->setParameter('user', $user)
//            ->setParameter('category', $category)
//             ->getQuery();
//        
//        return $q->getResult();
//    } 
    
    //used
    public function findExpensesByMonth($month, $year, $user, $category)
    {
        $emConfig = $this->getEntityManager()->getConfiguration();
        $emConfig->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
        $emConfig->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');
        
        $q = $this
            ->createQueryBuilder('e')
            ->where('MONTH(e.date) = :month') 
            ->andWhere('YEAR(e.date) = :year')
            ->andWhere('e.user = :user')
            ->orderBy('e.category', 'ASC')
            ->setParameter('month', $month)
            ->setParameter('year', $year)
            ->setParameter('user', $user) 
            ->getQuery();
        
        return $q->getResult();
    } 
    
    //used
    public function getSumByMonth($month, $year, $user)
    {
        $emConfig = $this->getEntityManager()->getConfiguration();
        $emConfig->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
        $emConfig->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');
        
        $q = $this->createQueryBuilder('e')
            ->add('select', 'SUM(e.price)')
            ->where('MONTH(e.date) = :month')
            ->andWhere('YEAR(e.date) = :year')
            ->andWhere('e.user = :user') 
            ->setParameter('month', $month)
            ->setParameter('year', $year)
            ->setParameter('user', $user)
            ->getQuery();

        return $q->getSingleScalarResult();
    }
    
    //used
    public function findSumBetweenDates($start_date, $end_date, $user)
    {
        $q = $this
            ->createQueryBuilder('e')
            ->add('select', 'SUM(e.price)')
            ->where('e.date >= :start_date')
            ->andWhere('e.date < :end_date')
            ->andWhere('e.user = :user')
            ->setParameter('start_date', $start_date)
            ->setParameter('end_date', $end_date)
            ->setParameter('user', $user)
             ->getQuery();
        
        return $q->getSingleScalarResult();
    }
    
//    public function findForCat($user, $q)
//    {
//         $em = $this->getEntityManager();
//        $query = $em->createQuery('SELECT e FROM AcmeBudgetTrackerBundle:Expense e WHERE e.user = ?1'.$q);
//        $query->setParameter(1, $user);
//        return $query->getResult();
//    }
  
    public function findByCategoriesAndDates($start_date, $end_date, $q, $user)
    {
         $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT e FROM AcmeBudgetTrackerBundle:Expense e WHERE e.user = ?1 AND e.date >= ?2 AND e.date < ?3'.$q. 'ORDER BY e.category, e.date');
        $query->setParameter(1, $user);
        $query->setParameter(2, $start_date);
        $query->setParameter(3, $end_date);
        return $query->getResult();
    }
    
    public function findExpensesCountByCategory($category, $user)
    {
        $q = $this
            ->createQueryBuilder('e')
            ->select('COUNT(e.id)') 
            ->where('e.category >= :category')
            ->andWhere('e.user = :user')
            ->setParameter('category', $category)
            ->setParameter('user', $user)
             ->getQuery();
        
        return $q->getSingleScalarResult();
    }
}