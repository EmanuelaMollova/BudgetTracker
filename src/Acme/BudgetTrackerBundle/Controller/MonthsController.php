<?php

namespace Acme\BudgetTrackerBundle\Controller;

use Acme\BudgetTrackerBundle\Controller\Controller as Controller;
use Acme\BudgetTrackerBundle\Entity\Month;
use Acme\BudgetTrackerBundle\Form\Type\MonthType;
use Symfony\Component\HttpFoundation\Request;

class MonthsController extends Controller
{
    public function monthsAction()
    {
        $data = null;
        $budgets = null;
        $this->setUser();
        $month_repository = $this->setRepository('Month');
        $expense_repository = $this->setRepository('Expense');
        $month = new Month();
        $form = $this->createForm(new MonthType(), $month);
        
        $all_months = $month_repository->findByUser($this->user); 
        $all_months_count = count($all_months);
        $months_for_chart = (array_slice($all_months, $all_months_count-12, 12));
        
        $months = array();
        $names = array();
        $spent = array();
        $budget = array();
        $saved = array();
        
        foreach ($all_months as $m)
        {
            array_push($months, $m->getName());
            
            $sum = $expense_repository->getSumByMonth($this->user, $m->getDate()->format('Y'), $m->getDate()->format('m'));
            if(!$sum){
                $sum = 0;
            }
            
            array_push($spent, $sum);
            
           array_push($budget, $m->getBudget());
           array_push($saved, $m->getBudget() - $sum);
  
        }  
        
        foreach ($months_for_chart as $m)
        {
            array_push($names, "'".$m->getName()."'");
        }
        
        if($names){
            $data = join($names, ', ');
        }
        
        $spent_joined = join(array_slice($spent, $all_months_count-12, 12), ', ');
        $budget_joined = join(array_slice($budget,$all_months_count-12, 12), ', ');

        return $this->render('AcmeBudgetTrackerBundle:Months:months.html.twig', array(
            'all_months' => $all_months,
            'data' => $data,
            'months' => $months,
            'names' => $names,
            'spent' => $spent,
            'spent_joined' => $spent_joined,
            'budget_joined' => $budget_joined,
            'budget' => $budget,
            'all_months_count' => $all_months_count,
            'saved' => $saved,
            'form' => $form->createView()
        ));
    }
    
    public function createMonthAction(Request $request)
    {
        $data = null;
        $budgets = null;
        $this->em = $this->getEM();
        $this->setUser();
        
        $month_repository = $this->setRepository('Month');
                       
        $all_months = $month_repository->findByUser($this->user);

        $month = new Month();
        $form = $this->createForm(new MonthType(), $month);

        if ($request->isMethod('POST')) {
            $form->bind($request);
            
             
            

            
            
            
            $dateObj = \DateTime::createfromformat('m-Y', $month->getDate());
            $month->setDate($dateObj);
            $name = $dateObj->format('F').' '.$dateObj->format('Y');
            $month->setName($name);
            
                        $same = $month_repository->
                    countByNameAndUser($month->getName(), $this->user);

                        var_dump($same);
            //die();
                        
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
                'data' => $data,
                'budgets' => $budgets,
                'form' => $form->createView()));    
    }
}