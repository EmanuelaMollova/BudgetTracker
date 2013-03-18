<?php

namespace Acme\BudgetTrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MonthsController extends Controller
{
    public function monthsAction()
    {
        return $this->render('AcmeBudgetTrackerBundle:Months:months.html.twig');
    }
}