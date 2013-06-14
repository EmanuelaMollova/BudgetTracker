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
        $this->setUser();
        $month_repository = $this->setRepository('Month');
        $month = new Month();
        $form = $this->createForm(new MonthType(), $month);
        
        $all_months = $month_repository->findByUser($this->user); 
        
        $names = array();
        
        foreach ($all_months as $mn)
        {
            array_push($names, $mn->getName());
        }
        
        $names = json_encode($names);
        
        var_dump($names);
        
        return $this->render('AcmeBudgetTrackerBundle:Months:months.html.twig', array(
            'all_months' => $all_months,
            'names' => $names,
            'form' => $form->createView()
        ));
    }
    
    public function createMonthAction(Request $request)
    {
        $this->em = $this->getEM();
        $this->setUser();
        
        $month_repository = $this->setRepository('Month');
                       
        $all_months = $month_repository->findByUser($this->user);

        $month = new Month();
        $form = $this->createForm(new MonthType(), $month);

        if ($request->isMethod('POST')) {
            $form->bind($request);
            
            $same = $month_repository->
                    countByNameAndUser($month->getName(), $this->user);

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
                'form' => $form->createView()));    
    }
}