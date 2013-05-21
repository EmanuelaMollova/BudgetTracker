<?php

namespace Acme\BudgetTrackerBundle\Controller;

use Acme\BudgetTrackerBundle\Controller\Controller as Controller;
use Acme\BudgetTrackerBundle\Entity\Expense;
use Acme\BudgetTrackerBundle\Form\Type\ExpenseType;
use Symfony\Component\HttpFoundation\Request;

class ExpensesController extends Controller
{
    private $user;
    
    private function init()
    {
        $this->user = $this->container->get('security.context')->getToken()->getUser();
        $this->repository = $this->setRepository('Expense');
    }

    public function expensesAction()
    {
        $this->init();
        
        //If the user has categories, show the form for adding expenses
        $newcommer = false;
        
        //Take the count of all categories for the current user to see if he is brand new
        $category_repository = $this->setRepository('Category');
        //TODO taka ili da ima countByUser?
        $all_categories = $category_repository->findByUser($this->user);
        
        if(count($all_categories) == 0){
            $newcommer = true;
        }
        
        //Set the current date for searching all expenses for today
        $today = new \DateTime;
        $date = $today->format('d-m-Y');
        
        $expenses_for_today = $this->repository->findExpensesForDate($this->user, $date);
        
        //Find the sum spent today
        $sum = 0;
        
        foreach ($expenses_for_today as $exp) {
            $sum += $exp->getPrice();
        }
    
        $expense = new Expense();
        
        //Set the current date to be default
        $expense->setDate($date);

        $form = $this->createForm(new ExpenseType($this->user), $expense);

        return $this->render(
                'AcmeBudgetTrackerBundle:Expenses:expenses.html.twig', array(
                    'newcommer' => $newcommer,
                    'expenses' => $expenses_for_today,
                    'sum' => $sum,
                    'form' => $form->createView()));
    }

    public function createExpenseAction(Request $request)
    {   
        $this->init();
        $newcommer = false;
        $this->em = $this->getDoctrine()->getEntityManager();
        
        $expense = new Expense();
        $form = $this->createForm(new ExpenseType($this->user), $expense);
        
        //$categories = $this->repository->findByUser($this->user);

        if ($request->isMethod('POST')) {
            $form->bind($request);
            
            //Convert string date to DateTime object and send it to database as object
            //$dateObj = \DateTime::createfromformat('d-m-Y', $expense->getDate());
            //$expense->setDate($dateObj);
            
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