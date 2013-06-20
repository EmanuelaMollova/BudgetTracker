<?php

namespace Acme\BudgetTrackerBundle\Controller;

use Acme\BudgetTrackerBundle\Controller\Controller as Controller;
use Acme\BudgetTrackerBundle\Entity\DebtLoan;
use Acme\BudgetTrackerBundle\Form\Type\DebtLoanType;
use Symfony\Component\HttpFoundation\Request;

class DebtsLoansController extends Controller
{
    private function init()
    {
        $this->setUser();
        $this->repository = $this->setRepository('Expense');
    }
    
    public function debtsLoansAction(Request $request)
    {
        
        $this->init();

        $category_repository = $this->setRepository('Category'); 
        
        $today = new \DateTime('now');
        $today = $today->format('d-m-Y');
        
        $debtloan = new DebtLoan();
        $debtloan->setDate($today);
        $form = $this->createForm(new DebtLoanType(), $debtloan);
        
        if ($request->isMethod('POST')) {
            $form->bind($request);
            
            //Convert string date to DateTime object and send it to database as object
            $date_object = \DateTime::createfromformat('d-m-Y', $debtloan->getDate());
            $debtloan->setDate($date_object);
            
            $this->em = $this->getEM();
            
            if ($form->isValid()) {
                $debtloan->setUser($this->user);             
                $this->em->persist($debtloan);
                $this->em->flush();

                return $this->redirect($this->generateUrl('debts_loans'));
            }  
        }
        
        $dl_repository = $this->setRepository('DebtLoan');
        
        $active_debts = $dl_repository->findByCategory($this->user, 0);
        $active_loans = $dl_repository->findByCategory($this->user, 1);
        $passive_debts = $dl_repository->findByCategory($this->user, 0, 1);
        $passive_loans = $dl_repository->findByCategory($this->user, 1, 1);
        
                
        return $this->render('AcmeBudgetTrackerBundle:DebtsLoans:debtsloans.html.twig', array(
            'active_debts' => $active_debts,
            'active_loans' => $active_loans,
            'passive_debts' => $passive_debts,
            'passive_loans' => $passive_loans,
            'notifications' => $this->notifications,
            'form' => $form->createView()
        ));
    }
    
    public function setNotificationsAction() {
        $this->setNotifications();
        return $this->render('AcmeBudgetTrackerBundle::navbar.html.twig', array(
            'debts' => $this->notifications,
        ));
    }

        public function returnAction($id)
    {   
          $dl_repository = $this->setRepository('DebtLoan');
          $dl = $dl_repository->findById($id);
          $dl = $dl[0];
          $dl->setReturned(1 - $dl->getReturned());
          $this->em = $this->getEM();


            $this->em->persist($dl);
            $this->em->flush();
             return $this->redirect($this->generateUrl('debts_loans'));
            
         
     }
}
