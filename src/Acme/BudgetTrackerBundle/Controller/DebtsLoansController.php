<?php

namespace Acme\BudgetTrackerBundle\Controller;

use Acme\BudgetTrackerBundle\Controller\Controller as Controller;
use Acme\BudgetTrackerBundle\Entity\Expense;
use Acme\BudgetTrackerBundle\Form\Type\DebtLoanType;
use Symfony\Component\HttpFoundation\Request;

/*
 * Adds new Debts/Loans and displays existing ones with an option to mark them as
 * returned/not returned
 */
class DebtsLoansController extends Controller
{
    /*
     * Creates new Debts and Loans and displays existing ones
     */
    public function debtsLoansAction(Request $request)
    {      
        $this->setVariables($newcommer = false, $month = false);
        
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
            
            if ($form->isValid()) {
                $debtloan->setUser($this->user);             
                $this->em->persist($debtloan);
                $this->em->flush();

                return $this->redirect($this->generateUrl('debts_loans'));
            }  
        }        
        
        $active_debts = $this->expense_repository->findExpensesByCategory($this->user, $this->debts_id);       
        $active_loans = $this->expense_repository->findExpensesByCategory($this->user, $this->loans_id);
        $passive_debts = $this->expense_repository->findExpensesByCategory($this->user, $this->debts_id, 1);
        $passive_loans = $this->expense_repository->findExpensesByCategory($this->user, $this->loans_id, 1);
                        
        return $this->render('AcmeBudgetTrackerBundle:DebtsLoans:debtsloans.html.twig', array(
            'active_debts' => $active_debts,
            'active_loans' => $active_loans,
            'passive_debts' => $passive_debts,
            'passive_loans' => $passive_loans,
            'form' => $form->createView()
        ));
    }

    /*
     * Marks debts and loans as returned/not returned
     */
    public function returnAction($id)
    {   
        $this->setVariables($newcommer = false, $month = false, $em = true, $expense = true, $category = false, $ids = false);

        $dl = $this->expense_repository->findById($id);
        
        if(!$dl){
            return $this->render(
                'AcmeBudgetTrackerBundle:Error:error.html.twig', array(
                'item' => 'month'
            ));
        }
        
        $dl = $dl[0];
        $dl->setReturned(1 - $dl->getReturned());
          
        $this->em->persist($dl);
        $this->em->flush();
        
        return $this->redirect($this->generateUrl('debts_loans'));     
     }
}
