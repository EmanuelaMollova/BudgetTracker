<?php

namespace Acme\BudgetTrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
        return $this->render('AcmeBudgetTrackerBundle:Home:index.html.twig');
    }
}
