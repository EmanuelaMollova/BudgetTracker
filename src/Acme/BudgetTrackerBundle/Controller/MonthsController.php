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
        
        $month = new Month();
        $form = $this->createForm(new MonthType(), $month);

        if ($request->isMethod('POST')) {
            $form->bind($request);

            if ($form->isValid()) {
                $month->setUser($this->user);
                $this->em->persist($month);
                $this->em->flush();

                return $this->redirect($this->generateUrl('months'));
            }
        }
                
        return $this->render(
            'AcmeBudgetTrackerBundle:Months:months.html.twig', array(
                'form' => $form->createView()));    
    }
}