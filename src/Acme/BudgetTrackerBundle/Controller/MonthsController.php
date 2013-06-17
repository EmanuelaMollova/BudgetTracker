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
        
        $all_months = array_reverse($month_repository->findByUser($this->user)); 
        
        $names = array();
        $spent = array();
        $budget = array();
        
        foreach ($all_months as $m)
        {
            array_push($names, "'".$m->getName()."'");
            
            $sum = $expense_repository->getSumByMonth($this->user, $m->getDate()->format('Y'), $m->getDate()->format('m'));
            if(!$sum){
                $sum = 0;
            }
            
            array_push($spent, $sum);
            
           array_push($budget, $m->getBudget());
  
        }  
        
        
        
        if($names){
            $data = join($names, ', ');
        }
        
        $spent = join($spent, ', ');
        $budget = join($budget, ', ');

        return $this->render('AcmeBudgetTrackerBundle:Months:months.html.twig', array(
            'all_months' => $all_months,
            'data' => $data,
            'spent' => $spent,
            'budget' => $budget,
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