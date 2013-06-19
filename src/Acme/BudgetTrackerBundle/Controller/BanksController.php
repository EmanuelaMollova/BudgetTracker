<?php

namespace Acme\BudgetTrackerBundle\Controller;

use Acme\BudgetTrackerBundle\Controller\Controller as Controller;

class BanksController extends Controller
{
    public function banksAction()
    {
        
         $this->setUser();
        $month_repository = $this->setRepository('Month');
        $expense_repository = $this->setRepository('Expense');
        
        $all_months = $month_repository->findByUser($this->user); 
        $all_months_count = count($all_months);
        
        $spent = array();
        $budget = array();
        $saved = array();
        
        foreach ($all_months as $m)
        {
            $sum = $expense_repository->getSumByMonth($m->getDate()->format('m'), $m->getDate()->format('Y'),  $this->user);
            if(!$sum){
                $sum = 0;
            }
            
            array_push($spent, $sum);           
            array_push($budget, $m->getBudget());
            array_push($saved, $m->getBudget() - $sum);
        }  
        
        $saved_sum = 0;
        foreach ($saved as $s){
            $saved_sum += $s;
        }
        
        var_dump($saved_sum);
        
        return $this->render(
            'AcmeBudgetTrackerBundle:Banks:banks.html.twig', array(
                'saved_sum' => $saved_sum
            ));
    }
}
