<?php

namespace Acme\BudgetTrackerBundle\Controller;

use Acme\BudgetTrackerBundle\Controller\Controller as Controller;
use Acme\BudgetTrackerBundle\Entity\Expense;
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
        
        $debtloan = new Expense();
        $debtloan->setDate($today);
        $form = $this->createForm(new DebtLoanType($this->user), $debtloan);
        
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
//        $id_debts_cat = $category_repository->findByNameUser($this->user, 'Debts');
//        $id_debts_cat = $id_debts_cat[0]->getId();
//        
//        $id_loans_cat = $category_repository->findByNameUser($this->user, 'Loans');
//        $id_loans_cat = $id_loans_cat[0]->getId();
        $this->setDebtsLoansIds();
        
        $active_debts = $this->repository->findByCategory($this->user, $this->debts_id);
        
        //var_dump($active_debts);
        
        $active_loans = $this->repository->findByCategory($this->user, $this->loans_id);
        $passive_debts = $this->repository->findByCategory($this->user, $this->debts_id, 1);
        $passive_loans = $this->repository->findByCategory($this->user, $this->loans_id, 1);
        
                
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
          $dl_repository = $this->setRepository('Expense');
          $dl = $dl_repository->findById($id);
          $dl = $dl[0];
          $dl->setReturned(1 - $dl->getReturned());
          $this->em = $this->getEM();


            $this->em->persist($dl);
            $this->em->flush();
             return $this->redirect($this->generateUrl('debts_loans'));
            
         
     }
}
