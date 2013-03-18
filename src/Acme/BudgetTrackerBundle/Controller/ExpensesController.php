<?php

namespace Acme\BudgetTrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ExpensesController extends Controller
{
    public function expensesAction()
    {
        return $this->render('AcmeBudgetTrackerBundle:Expenses:expenses.html.twig');
    }
}