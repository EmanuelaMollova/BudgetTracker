<?php

namespace Acme\BudgetTrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GoodbyeController extends Controller
{
    public function goodbyeAction()
    {
        return $this->render('AcmeBudgetTrackerBundle:Goodbye:goodbye.html.twig');
    }
}