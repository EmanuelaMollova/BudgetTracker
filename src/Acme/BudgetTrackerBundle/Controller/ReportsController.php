<?php

namespace Acme\BudgetTrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ReportsController extends Controller
{
    public function reportsAction()
    {
        return $this->render('AcmeBudgetTrackerBundle:Reports:reports.html.twig');
    }
}