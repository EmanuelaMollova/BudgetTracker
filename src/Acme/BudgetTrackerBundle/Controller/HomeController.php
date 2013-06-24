<?php

namespace Acme\BudgetTrackerBundle\Controller;

use Acme\BudgetTrackerBundle\Controller\Controller as Controller;
use Acme\BudgetTrackerBundle\Entity\Category;

/*
 * Takes care of the index page
 */
class HomeController extends Controller
{
    /*
     * Creates Debts and Loans categories for the user if they don't already exist
     */
    private function createCategory($name, $repository, $user, $em)
    {       
        $count = $repository->countCategoriesByName($name, $user);
        if($count == 0){
            $category = new Category();
            $category->setUser($user);
            $category->setName($name);
            
            $em->persist($category);
            $em->flush();
        }
    }
    
    /* 
     * Finds if the user is brand new or experienced. In the second case finds
     * all his expenses for the current month, the sum, spent for them, the
     * budget for the month (if set) and remaining money and gives them to the 
     * template.
     */
    public function indexAction()
    {
        $this->setVariables($newcommer = true, $month = true, $em = true, $ids = false);
               
        $this->createCategory('Loans', $this->category_repository, $this->user, $this->em);
        $this->createCategory('Debts', $this->category_repository, $this->user, $this->em);

        $template = 'AcmeBudgetTrackerBundle:Home:index.html.twig';
        
        if($this->number_of_user_categories == 0){                
            return $this->render($template, array(
                'newcommer' => true)); 
        } else {
            $today = new \DateTime();
            
            $this->setDLIDs();
            
            $expenses_for_current_month = $this->expense_repository->
               findExpensesByMonth($today->format('m'), $today->format('Y'), $this->user, $this->debts_id); 

            if(!$expenses_for_current_month){
                return $this->render($template, array(
                    'newcommer' => false,
                    'expenses_for_current_month' => null));      
            } else {              
                $first_category = $expenses_for_current_month[0]->getCategory()->getName();

                $spent_for_current_month = $this->expense_repository->
                    findSumOfExpensesByMonth($today->format('m'), $today->format('Y'), $this->user, $this->debts_id);

                $current_month = $this->month_repository
                    ->findMonthByUser($today->format('m'), $today->format('Y'), $this->user); 

                if($current_month){
                    $budget_for_current_month = $current_month[0]->getBudget();
                    $remaining = number_format($budget_for_current_month - $spent_for_current_month, 2, '.', ''); 
                } else {
                    $budget_for_current_month = null;
                    $remaining = 0;
               }        
            }
            
            return $this->render($template, array(
                'newcommer' => false,
                'first_category' => $first_category,
                'expenses_for_current_month' => $expenses_for_current_month,
                'budget_for_current_month' => $budget_for_current_month,
                'spent_for_current_month' => $spent_for_current_month,              
                'remaining' => $remaining
            ));
        }
    }
}