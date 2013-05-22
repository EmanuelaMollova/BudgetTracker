<?php

namespace Acme\BudgetTrackerBundle\Controller;

use Acme\BudgetTrackerBundle\Controller\Controller as Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
        $this->repository = $this->setRepository('Expense');
        $this->user = $this->container->get('security.context')->getToken()->getUser();
        
        $cat_repo = $this->setRepository('Category');
        $all_categories = $cat_repo->findByUser($this->user);
        
        $all = array();
        
        //Get the cuurent month
        $today = new \DateTime;
        $date = $today->format('m-Y');
        
//        foreach ($all_categories as $cat)
//        {
//            //For each category creates array with name $category with expenses for this category for the given month
//            //and pushes all this arrays into $all
//            
//            $var = strtolower($cat->getName());
//            array_push($all, $$var = $this->repository->
//                    findExpensesForMonthAndCat($this->user, $date, $cat->getId()));
//        }
        
        $expenses = $this->repository->findExpensesForMonth($this->user, $date);
        
        $first_cat = $expenses[0]->getCategory()->getName();
        
//        $total_sum = 0;
//        
//        foreach ($all as $al)
//        {
//            foreach($al as $a)
//            {
//                $total_sum += $a->getPrice();
//            }
//        }
//        
//        echo $total_sum;
        
        $newcommer = true;
           
        $number_of_categories = $cat_repo->countByUser($this->user);
        
        if($number_of_categories){
            $newcommer = false;
        }
        
        //$expenses = array( array(1, 2, 3), 2, array(3, 5), 4, array(5, 'star', 'pool'), 6);
        
        return $this->render('AcmeBudgetTrackerBundle:Home:index.html.twig', array(
            'newcommer' => $newcommer,
            'expenses' => $expenses,
            'first_cat' => $first_cat
                    ));
    }
}