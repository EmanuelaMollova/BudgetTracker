<?php

namespace Acme\BudgetTrackerBundle\Controller;

use Acme\BudgetTrackerBundle\Controller\Controller as Controller;

class GoodbyeController extends Controller
{
    public function goodbyeAction()
    {
        return $this->render('AcmeBudgetTrackerBundle:Goodbye:goodbye.html.twig');
    }
}