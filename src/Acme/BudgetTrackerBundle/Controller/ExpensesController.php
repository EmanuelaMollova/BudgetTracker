<?php

namespace Acme\BudgetTrackerBundle\Controller;

use Acme\BudgetTrackerBundle\Controller\Controller as Controller;
use Acme\BudgetTrackerBundle\Entity\Expense;
use Acme\BudgetTrackerBundle\Form\Type\ExpenseType;
use Symfony\Component\HttpFoundation\Request;

/*
 * Creates new expenses and shows all the expenses for the current day
 */
class ExpensesController extends Controller
{
    /*
     * If the user has some categories displays the form for adding new expenses.
     * Finds all the expenses for the day and their sum. If the method is post -
     * creates a new expense and adds it to the database.
     */
    
    //user, newcommer, expense_repo, category_repo
    public function expensesAction(Request $request)
    {
        $newcommer = false;
        
        $category_repository = $this->setRepository('Category');
        $this->setUser();
        $number_of_user_categories = $category_repository->countByUser($this->user);
        
        if($number_of_user_categories == 0){
            $newcommer = true;
        }

        //Set the current date for searching all expenses for today     
        $from_date = new \DateTime();
        $from_date->setTime(0, 0, 0);
        
        $to_date = new \DateTime();
        $to_date->modify('+1 day');
        $to_date->setTime(0, 0, 0);
        
        $this->repository = $this->setRepository('Expense');
        $this->setDebtsLoansIds();
        
        $expenses_for_today = $this->repository->findExpensesBetweenDates($from_date, $to_date, $this->user, $this->debts_id);
        $spent_for_today = $this->repository->findSumBetweenDates($from_date, $to_date, $this->user, $this->debts_id);
        
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
                
                $this->em = $this->getEM();
                $this->em->persist($expense);
                $this->em->flush();

                return $this->redirect($this->generateUrl('expenses'));
            }
      
            return $this->render(
                'AcmeBudgetTrackerBundle:Expenses:expenses.html.twig', array(
                    'newcommer' => $newcommer,  
                    'form' => $form->createView()));   
        } else {

        return $this->render(
                'AcmeBudgetTrackerBundle:Expenses:expenses.html.twig', array(
                    'newcommer' => $newcommer,
                    'expenses_for_today' => $expenses_for_today,
                    'spent_for_today' => $spent_for_today,
                    'form' => $form->createView()));
        }
    } 
}

//------------------------------------------------------------------------------

//TODO 1. Add several expenses with one submit
//TODO 2. AJAX?

//user
//    public function createExpenseAction(Request $request)
//    {   
//        $this->init();
//        $newcommer = false;
//        $this->em = $this->getEM();
//        
//        $expense = new Expense();
//        $form = $this->createForm(new ExpenseType($this->user), $expense);
//
//        if ($request->isMethod('POST')) {
//            $form->bind($request);
//            
//            //Convert string date to DateTime object and send it to database as object
//            $date_object = \DateTime::createfromformat('d-m-Y', $expense->getDate());
//            $expense->setDate($date_object);
//            
//            if ($form->isValid()) {
//                $expense->setUser($this->user);             
//                $this->em->persist($expense);
//                $this->em->flush();
//
//                return $this->redirect($this->generateUrl('expenses'));
//            }
//      
//            return $this->render(
//                'AcmeBudgetTrackerBundle:Expenses:expenses.html.twig', array(
//                    'newcommer' => $newcommer,  
//                    'form' => $form->createView()));   
//        }
//    }