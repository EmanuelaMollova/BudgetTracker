<?php

namespace Acme\BudgetTrackerBundle\Controller;

use Acme\BudgetTrackerBundle\Controller\Controller as Controller;

/*
 * Takes care of the index page
 */
class HomeController extends Controller
{
    /*
     * Finds if the user is brand new or experienced. In the second case finds
     * all his expenses for the current month, the sum, spent for them and the
     * budget for the month (if set) and gives them to the template.
     */
    public function indexAction()
    {
        $this->setUser();               
        $category_repository = $this->setRepository('Category');    
        $number_of_categories = $category_repository->countByUser($this->user);
        
        if($number_of_categories == 0){
            $newcommer = true;          
        } else {
            $newcommer = false;
            
            $today = new \DateTime;
            $current_month_string = $today->format('m-Y');
            $expense_repository = $this->setRepository('Expense');
            $expenses_for_current_month = $expense_repository->findExpensesForMonth($this->user, $current_month_string); 
            
            if(!$expenses_for_current_month){
                echo "GO ADD EXPENSES";
            } else {
                $first_category = $expenses_for_current_month[0]->getCategory()->getName();
            
                $sum_for_current_month = $expense_repository->getSumByMonthAndUser($current_month_string, $this->user);     
                $current_month_object = $this->setRepository('Month')->findBy(array('name' => $current_month_string, 'user' => $this->user));        
                $budget_for_current_month = $current_month_object[0]->getBudget();
            }         
        }
        
        return $this->render('AcmeBudgetTrackerBundle:Home:index.html.twig', array(
            'newcommer' => $newcommer,
            'expenses' => $expenses_for_current_month,
            'first_category' => $first_category,
            'sum_for_current_month' => $sum_for_current_month,
            'budget_for_current_month' => $budget_for_current_month
        ));
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
//            //For each category creates array with name $category with expenses for this category for the given month
//            //and pushes all this arrays into $all
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