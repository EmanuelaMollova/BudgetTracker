<?php

namespace Acme\BudgetTrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;

class MonthsController extends BaseController
{
    public function monthsAction()
    {
        return $this->render('AcmeBudgetTrackerBundle:Months:months.html.twig');
    }
}