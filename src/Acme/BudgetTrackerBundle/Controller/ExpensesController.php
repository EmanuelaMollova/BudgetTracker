<?php

namespace Acme\BudgetTrackerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Acme\BudgetTrackerBundle\Controller\Controller as Controller;
use Acme\BudgetTrackerBundle\Entity\Expense;
use Acme\BudgetTrackerBundle\Form\Type\ExpenseType;

/*
 * Creates new expenses and displays all the expenses for the current day
 */
class ExpensesController extends Controller
{
    /*
     * If the user has some categories, displays the form for adding new expenses.
     * Finds all the expenses for the day and their sum. If the method is post -
     * creates a new expense and adds it to the database.
     */
    public function expensesAction(Request $request)
    {        
        $this->setVariables($newcomer = true, $month = false);

        //Set the current date for searching all expenses for today     
        $from_date = new \DateTime();
        $from_date->setTime(0, 0, 0);
        
        $to_date = new \DateTime();
        $to_date->modify('+1 day');
        $to_date->setTime(0, 0, 0);
        
        $expenses_for_today = $this->expense_repository->
            findExpensesBetweenDates($from_date, $to_date, $this->user, $this->debts_id);
        
        $payments_for_today = $this->bill_payment_repository->
            findPaymentsBetweenDates($this->user, $from_date, $to_date); 
          
        $spent_for_today = $this->expense_repository->
            findSumOfExpensesBetweenDates($from_date, $to_date, $this->user, $this->debts_id);
        
        $spent_for_payments_today = $this->bill_payment_repository->
            findSumOfPaymentsBetweenDates($from_date, $to_date, $this->user);
        
        //Create the form for adding new expenses
        $today = new \DateTime('now');
        $today = $today->format('d-m-Y');
        
        $expense = new Expense();
        $expense->setDate($today);
        $form = $this->createForm(new ExpenseType($this->user), $expense);
        
        if ($request->isMethod('POST')) {
            $form->bind($request);
            
            //Convert string date to DateTime object and send it to the database as object
            $date_object = \DateTime::createfromformat('d-m-Y', $expense->getDate());
            $expense->setDate($date_object);
            
            if ($form->isValid()) {               
                $expense->setUser($this->user);

                $this->em->persist($expense);
                $this->em->flush();

                return $this->redirect($this->generateUrl('expenses'));
            }
      
            return $this->render(
                'AcmeBudgetTrackerBundle:Expenses:expenses.html.twig', array(
                    'newcomer' => $this->newcomer,  
                    'form' => $form->createView()));   
        } else {

        return $this->render(
                'AcmeBudgetTrackerBundle:Expenses:expenses.html.twig', array(
                    'newcomer' => $this->newcomer,
                    'expenses_for_today' => $expenses_for_today,
                    'spent_for_today' => $spent_for_today,
                    'payments_for_today' => $payments_for_today,
                    'spent_for_payments_today' => $spent_for_payments_today,
                    'form' => $form->createView()));
        }
    }
}