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
        $count = $repository->countByName($name, $user);
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
    
    //user, em, dlids, expense_repo, category_repo, month_repo
    public function indexAction()
    {
        $this->setUser();               
        $category_repository = $this->setRepository('Category');
        $this->em = $this->getEM();
               
        $this->createCategory('Loans', $category_repository, $this->user, $this->em);
        $this->createCategory('Debts', $category_repository, $this->user, $this->em);
        
        $number_of_user_categories = $category_repository->countByUser($this->user);
        
        $template = 'AcmeBudgetTrackerBundle:Home:index.html.twig';
        
        if($number_of_user_categories == 0){                
            return $this->render($template, array(
                'newcommer' => true
            ));
        } else {                     
            $this->setDebtsLoansIds();
            $today = new \DateTime;
            
            $expense_repository = $this->setRepository('Expense');
            $expenses_for_current_month = $expense_repository->
                findExpensesByMonth($today->format('m'), $today->format('Y'), $this->user, $this->debts_id); 
             
            if(!$expenses_for_current_month){
                return $this->render($template, array(
                    'newcommer' => false,
                    'expenses_for_current_month' => null
                ));      
            } else {              
                $first_category = $expenses_for_current_month[0]->getCategory()->getName();

                $spent_for_current_month = $expense_repository->
                    getSumByMonth($today->format('m'), $today->format('Y'), $this->user);

                $current_month = $this->setRepository('Month')
                    ->findMonth($today->format('m'), $today->format('Y'), $this->user); 

                if($current_month){
                    $budget_for_current_month = $current_month[0]->getBudget();
                    $remaining = number_format($budget_for_current_month - $spent_for_current_month, 2, '.', ''); 
                } else {
                    $budget_for_current_month = null;
                    $remaining = null;
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
 
//------------------------------------------------------------------------------

//$all_categories = $cat_repo->findByUser($this->user);

// Another decision with Array of Arrays
// 
//        $all = array();
//        
//        foreach ($all_categories as $cat)
//        {
//            //For each category creates array with name $category with expenses 
//            //for this category for the given month and pushes all this arrays into $all
//            
//            $var = strtolower($cat->getName());
//            array_push($all, $$var = $this->repository->
//                    findExpensesForMonthAndCat($this->user, $date, $cat->getId()));
//        }  
//            
//        $total_sum = 0;
//        
//        foreach ($all as $al) {
//            foreach($al as $a) {
//                $total_sum += $a->getPrice();
//            }
//        }
//        
//        echo $total_sum;