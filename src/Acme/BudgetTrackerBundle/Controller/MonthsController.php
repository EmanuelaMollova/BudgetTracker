<?php

namespace Acme\BudgetTrackerBundle\Controller;

use Acme\BudgetTrackerBundle\Controller\Controller as Controller;
use Acme\BudgetTrackerBundle\Entity\Month;
use Acme\BudgetTrackerBundle\Form\Type\MonthType;
use Acme\BudgetTrackerBundle\Entity\Transfer;
use Acme\BudgetTrackerBundle\Form\Type\TransferType;
use Symfony\Component\HttpFoundation\Request;

class MonthsController extends Controller
{
    /*
     * Finds all months, the budget, the sum which is spent and the sum which is saved
     * and gives this information to the chart and table in the template.
     */
    
    //user, month, expense
    public function monthsAction()
    {
        $this->setUser();
        $month_repository = $this->setRepository('Month');
        $expense_repository = $this->setRepository('Expense');
        $month = new Month();
        $form = $this->createForm(new MonthType(), $month);
        
        $all_months = $month_repository->findByUser($this->user); 
        $all_months_count = count($all_months);
        $months_for_chart = (array_slice($all_months, $all_months_count-12, 12));
        
        $month_names_for_chart = array();
        $spent = array();
        $budget = array();
        $saved = array();
        
        $this->setDebtsLoansIds();
        foreach ($all_months as $m)
        {
            $sum = $expense_repository->findSumByMonth($m->getDate()->format('m'), $m->getDate()->format('Y'),  $this->user, $this->debts_id);
            if(!$sum){
                $sum = 0;
            }
            
            array_push($spent, $sum);           
            array_push($budget, $m->getBudget());
            array_push($saved, $m->getBudget() - $m->getTransfered() - $sum);
        }  
        
        foreach ($months_for_chart as $m)
        {
            array_push($month_names_for_chart, "'".$m->getName()."'");
        }
        
        if($month_names_for_chart){
            $month_names_for_chart = join($month_names_for_chart, ', ');
        }
        
        $spent_joined = join(array_slice($spent, $all_months_count-12, 12), ', ');
        $budget_joined = join(array_slice($budget,$all_months_count-12, 12), ', ');

        return $this->render('AcmeBudgetTrackerBundle:Months:months.html.twig', array(
            'all_months' => $all_months,
            'all_months_count' => $all_months_count,          
            'month_names_for_chart' => $month_names_for_chart,
            'spent_joined' => $spent_joined,
            'budget_joined' => $budget_joined,           
            'spent' => $spent,           
            'budget' => $budget,
            'saved' => $saved,
            'form' => $form->createView()
        ));
    }
    
    /*
     * Creates new month
     */
    
    //user, month
    public function createMonthAction(Request $request)
    {
        $duplicate = false;
        
        $this->em = $this->getEM();
        $this->setUser();
        
        $month_repository = $this->setRepository('Month');
                       
        $all_months = $month_repository->findByUser($this->user);

        $month = new Month();
        $form = $this->createForm(new MonthType(), $month);

        if ($request->isMethod('POST')) {
            $form->bind($request);
            
            $date_obj = \DateTime::createfromformat('m-Y', $month->getDate());
            $month->setDate($date_obj);
            
            $name = $date_obj->format('F Y');
            $month->setName($name);
            
            $same = $month_repository->
                countByNameAndUser($month->getName(), $this->user);
 
            if ($same == 0 && $form->isValid()) {
                $month->setUser($this->user);
                $this->em->persist($month);
                $this->em->flush();

                return $this->redirect($this->generateUrl('months'));
            } else {
                $this->get('session')->setFlash('notice', 'The budget for this month is already set!');
                return $this->redirect($this->generateUrl('months'));
            }
        }
        
        return $this->render(
            'AcmeBudgetTrackerBundle:Months:months.html.twig', array(
                'all_months' => $all_months,
                'form' => $form->createView()));    
    }
    
    public function transferAction($id, Request $request)
    { 
        $this->setUser();
        $month_repository = $this->setRepository('Month');     
        $month = $month_repository->findById($id);
        
        $expense_repository = $this->setRepository('Expense');
        $sum = $expense_repository->getSumByMonth($month[0]->getDate()->format('m'), $month[0]->getDate()->format('Y'),  $this->user);
                
        $start_date = $month[0]->getDate()->modify('+1 month');
        $start_date = $start_date->format('m-Y');
        
        $transfer = new Transfer();
        $transfer->setMoney($month[0]->getBudget() - $sum - $month[0]->getTransfered());
        $form = $this->createForm(new TransferType(), $transfer);
        
         if ($request->isMethod('POST')) {
            $form->bind($request);
            
            if ($form->isValid() && ($transfer->getMoney() <= $month[0]->getBudget() - $sum - $month[0]->getTransfered())) {
            
            $month[0]->setTransfered($month[0]->getTransfered() + $transfer->getMoney());
           
            $date_obj = \DateTime::createfromformat('m-Y', $transfer->getDestination());
            $name = $date_obj->format('F Y');
           
            $destination = $month_repository->findByName($name);
            $this->em = $this->getEM();
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
            echo "NOT VALID";
        }
        
                return $this->render(
                'AcmeBudgetTrackerBundle:Months:transfer.html.twig', array(
               // 'start_date' => $start_date,
               // 'max' => $transfer->getMoney(),
               // 'id' => $id,
                'form' => $form->createView()
                
            ));  
     }
        
        return $this->render(
            'AcmeBudgetTrackerBundle:Months:transfer.html.twig', array(
                'start_date' => $start_date,
                'id' => $id,
                 'max' => $transfer->getMoney(),
                'form' => $form->createView()
            ));
    }
}