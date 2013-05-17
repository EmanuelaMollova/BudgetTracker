<?php

namespace Acme\BudgetTrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Acme\BudgetTrackerBundle\Entity\Expense;
use Acme\BudgetTrackerBundle\Form\Type\ExpenseType;

class ExpensesController extends BaseController
{
    public function expensesAction()
    {
        $expense = new Expense();
        $user = $this->container->get('security.context')->getToken()->getUser();
        $form = $this->createForm(new ExpenseType($user), $expense);
        
        return $this->render('AcmeBudgetTrackerBundle:Expenses:expenses.html.twig',
                array('form' => $form->createView()));
    }
}