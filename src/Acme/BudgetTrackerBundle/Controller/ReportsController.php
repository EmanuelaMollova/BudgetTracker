<?php

namespace Acme\BudgetTrackerBundle\Controller;

use Acme\BudgetTrackerBundle\Controller\Controller as Controller;

class ReportsController extends Controller
{
    public function reportsAction()
    {
        return $this->render('AcmeBudgetTrackerBundle:Reports:reports.html.twig');
    }
}