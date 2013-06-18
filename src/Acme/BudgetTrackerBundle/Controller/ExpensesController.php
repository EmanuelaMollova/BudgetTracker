<?php

namespace Acme\BudgetTrackerBundle\Controller;

use Acme\BudgetTrackerBundle\Controller\Controller as Controller;
use Acme\BudgetTrackerBundle\Entity\Expense;
use Acme\BudgetTrackerBundle\Form\Type\ExpenseType;
use Symfony\Component\HttpFoundation\Request;

/*
 * Creates new expenses and show all the expenses for the current day
 */
class ExpensesController extends Controller
{
    private function init()
    {
        $this->setUser();
        $this->repository = $this->setRepository('Expense');
    }

    /*
     * If the user has some categories displays the form for adding new expenses.
     * Finds all the expenses for the day and their sum.
     */
    
    //user, expense_repo, category_repo
    public function expensesAction()
    {
        $this->init();
        
        $newcommer = false;

        $category_repository = $this->setRepository('Category'); 
        $number_of_categories = $category_repository->countByUser($this->user);
        
        if($number_of_categories == 0){
            $newcommer = true;
        }
        
        //Set the current date for searching all expenses for today     
        $from_date = new \DateTime();
        $from_date->setTime(0, 0, 0);
        
        $to_date = new \DateTime();
        $to_date->modify('+1 day');
        $to_date->setTime(0, 0, 0);
        
        $expenses_for_today = $this->repository->findExpensesForDate($from_date, $to_date, $this->user);

        $spent_for_today = $this->repository->findSumBetweenDates($from_date, $to_date, $this->user);
        
        $today = new \DateTime('now');
        $today = $today->format('d-m-Y');
        
        $expense = new Expense();
        $expense->setDate($today);
        $form = $this->createForm(new ExpenseType($this->user), $expense);

        return $this->render(
                'AcmeBudgetTrackerBundle:Expenses:expenses.html.twig', array(
                    'newcommer' => $newcommer,
                    'expenses' => $expenses_for_today,
                    'spent_for_today' => $spent_for_today,
                    'form' => $form->createView()));
    }

    /*
     * Adds new expenses.
     */
    
    //user
    public function createExpenseAction(Request $request)
    {   
        $this->init();
        $newcommer = false;
        $this->em = $this->getEM();
        
        $expense = new Expense();
        $form = $this->createForm(new ExpenseType($this->user), $expense);

        if ($request->isMethod('POST')) {
            $form->bind($request);
            
            //Convert string date to DateTime object and send it to database as object
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
                    'newcommer' => $newcommer,  
                    'form' => $form->createView()));   
        }
    }
}

//------------------------------------------------------------------------------

//TODO 1. Add several expenses with one submit
//TODO 2. AJAX?