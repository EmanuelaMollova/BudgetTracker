<?php

namespace Acme\BudgetTrackerBundle\Controller;

use Acme\BudgetTrackerBundle\Controller\Controller as Controller;

class MonthsController extends Controller
{
    public function monthsAction()
    {
        return $this->render('AcmeBudgetTrackerBundle:Months:months.html.twig');
    }
}