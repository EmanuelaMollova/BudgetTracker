<?php

namespace Acme\BudgetTrackerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Acme\BudgetTrackerBundle\Controller\Controller as Controller;
use Acme\BudgetTrackerBundle\Entity\Month;
use Acme\BudgetTrackerBundle\Form\Type\MonthType;
use Acme\BudgetTrackerBundle\Entity\Transfer;
use Acme\BudgetTrackerBundle\Form\Type\TransferType;

/*
 * Takes care of adding budget for months and transfers
 */
class MonthsController extends Controller
{
    /*
     * Finds all months, the budget, the sum which is spent and the sum which is 
     * saved and gives this information to the chart and table in the template.
     */
    public function monthsAction(Request $request)
    {   
        $this->setVariables($newcomer = false, $month = true, $em = false);
        
        $month = new Month();
        $form = $this->createForm(new MonthType(), $month);
        
        $all_months = $this->month_repository->findMonthsByUser($this->user); 
        $all_months_count = count($all_months);
        if($all_months_count <= 12){
            $months_for_chart = $all_months;
        } else {
            $months_for_chart = (array_slice($all_months, $all_months_count-12, 12));
        }
        
        $month_names_for_chart = array();
        $spent = array();
        $budget = array();
        $saved = array();
        $loans = array();
        
        foreach ($all_months as $m) {
            $sum = $this->expense_repository->
                findSumOfExpensesByMonth($m->getDate()->format('m'), $m->getDate()->format('Y'),  $this->user, $this->debts_id);
                
            if(!$sum){
                $sum = 0;
            }
        
            $active_loans = $this->expense_repository->
                findSumOfExpensesByMonthAndCategory($m->getDate()->format('m'), $m->getDate()->format('Y'), $this->user, $this->loans_id);
            
            if(!$active_loans){
                $active_loans = 0;
            }
            
            array_push($spent, $sum);           
            array_push($budget, $m->getBudget());
            array_push($saved, $m->getBudget() - $m->getTransfered() - $sum);
            array_push($loans, $active_loans);
        }  
        
        foreach ($months_for_chart as $m) {
            array_push($month_names_for_chart, "'".$m->getName()."'");
        }
        
        if($month_names_for_chart){
            $month_names_for_chart = join($month_names_for_chart, ', ');
        }
        
        if($all_months_count <= 12){
            $spent_joined = join($spent, ', ');
            $budget_joined = join($budget, ', ');
        } else {
            $spent_joined = join(array_slice($spent, $all_months_count-12, 12), ', ');
            $budget_joined = join(array_slice($budget,$all_months_count-12, 12), ', ');
        }
         
        $current_route = $request->attributes->get('_route');
        
        if($current_route == 'months'){
            return $this->render('AcmeBudgetTrackerBundle:Months:months.html.twig', array(
            'all_months' => $all_months,
            'all_months_count' => $all_months_count,          
            'month_names_for_chart' => $month_names_for_chart,
            'spent_joined' => $spent_joined,
            'budget_joined' => $budget_joined,           
            'spent' => $spent,           
            'budget' => $budget,
            'saved' => $saved,
            'active_loans' => $loans,
            'form' => $form->createView()));
        } else {
            $saved_sum = 0;
            foreach ($saved as $s) {
                $saved_sum += $s;
            }
            
            return $this->render(
            'AcmeBudgetTrackerBundle:Banks:banks.html.twig', array(
                'saved_sum' => $saved_sum));
        }     
    }
    
    /*
     * Creates new month
     */
    public function createMonthAction(Request $request)
    {   
        $this->setVariables($newcomer = false, $month = true, $em = true, $expense = false, $category = false, $ids = false);
                       
        $all_months = $this->month_repository->findMonthsByUser($this->user);

        $month = new Month();
        $form = $this->createForm(new MonthType(), $month);

        if ($request->isMethod('POST')) {
            $form->bind($request);
            
            $date_obj = \DateTime::createfromformat('m-Y', $month->getDate());
            $month->setDate($date_obj);
            
            $name = $date_obj->format('F Y');
            $month->setName($name);
            
            $same = $this->month_repository->
                countMonthsByNameAndUser($month->getName(), $this->user);
 
            if ($same == 0 && $form->isValid()) {
                $month->setUser($this->user);
                $this->em->persist($month);
                $this->em->flush();

                return $this->redirect($this->generateUrl('months'));
            } else {
                $this->setFlash('The budget for this month is already set!');
                
                return $this->redirect($this->generateUrl('months'));
            }
        }
        
        return $this->render(
            'AcmeBudgetTrackerBundle:Months:months.html.twig', array(
                'all_months' => $all_months,
                'form' => $form->createView()));    
    }
    
    /*
     * Takes care of transfering money from one month to another
     */
    public function transferAction($id, Request $request)
    {           
        $this->setVariables($newcomer = false);
  
        $month = $this->month_repository->findById($id);
        if(!$month){
            return $this->render(
                'AcmeBudgetTrackerBundle:Error:error.html.twig', array(
                'item' => 'month'));
        }
        
        $sum = $this->expense_repository->
            findSumOfExpensesByMonth($month[0]->getDate()->format('m'), $month[0]->getDate()->format('Y'),  $this->user, $this->debts_id);

        $transfer = new Transfer();
        $transfer->setMoney($month[0]->getBudget() - $sum - $month[0]->getTransfered());
        $form = $this->createForm(new TransferType(), $transfer);
        
         if ($request->isMethod('POST')) {
            $form->bind($request);
            
            if ($form->isValid() && ($transfer->getMoney() <= $month[0]->getBudget() - $sum - $month[0]->getTransfered())) {
            
            $month[0]->setTransfered($month[0]->getTransfered() + $transfer->getMoney());
           
            $date_obj = \DateTime::createfromformat('m-Y', $transfer->getDestination());
            $name = $date_obj->format('F Y');
           
            $destination = $this->month_repository->findByName($name);
            if($destination){
                $destination[0]->setBudget($destination[0]->getBudget() + $transfer->getMoney());
                $this->em->persist($destination[0]);               
            } else {
                $new_month = new Month();
                $new_month->setUser($this->user);
                $new_month->setDate($date_obj);
                $new_month->setBudget($transfer->getMoney());
                $new_month->setName($date_obj->format('F Y'));
                $this->em->persist($new_month);
            }
            
            $this->em->persist($month[0]);
            $this->em->flush();

            return $this->redirect($this->generateUrl('months'));
 
        } else {
             $this->setFlash('You do not have so much money remaining!');
             
             return $this->redirect($this->generateUrl('transfer', array('id' => $id)));
        }
    }

    $start_date = $month[0]->getDate()->modify('+1 month');
    $start_date = $start_date->format('m-Y');   

    return $this->render(
        'AcmeBudgetTrackerBundle:Months:transfer.html.twig', array(
            'start_date' => $start_date,
            'id' => $id,
            'max' => $transfer->getMoney(),
            'form' => $form->createView()));
    }
}