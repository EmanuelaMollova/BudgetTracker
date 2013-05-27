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
        $month = new Month();
        $form = $this->createForm(new MonthType(), $month);
        
        return $this->render('AcmeBudgetTrackerBundle:Months:months.html.twig', array(
            'form' => $form->createView()
        ));
    }
    
    public function createMonthAction(Request $request)
    {
        $this->em = $this->getEM();
        $this->user = $this->container->get('security.context')->getToken()->getUser();
        
        $this->repo = $this->setRepository('Month');
        
                        
        $all_months = $this->repo->findByUser($this->user);
                
        echo count($all_months);
        die();
        
        
        $month = new Month();
        $form = $this->createForm(new MonthType(), $month);

        if ($request->isMethod('POST')) {
            $form->bind($request);
            
            $same = $this->repo->
                    countByNameAndUser($month->getName(), $this->user);
            
            echo $same;

            if ($same == 0 && $form->isValid()) {
                $month->setUser($this->user);
                $this->em->persist($month);
                $this->em->flush();

                return $this->redirect($this->generateUrl('months'));
            } else {
                echo "YOU BAD PERSON!!!";
            }
        }


        
        return $this->render(
            'AcmeBudgetTrackerBundle:Months:months.html.twig', array(
                'all_months' => $all_months,
                'form' => $form->createView()));    
    }
}