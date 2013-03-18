<?php

namespace Acme\BudgetTrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CategoriesController extends Controller
{
    public function categoriesAction()
    {
        return $this->render('AcmeBudgetTrackerBundle:Categories:categories.html.twig');
    }
}