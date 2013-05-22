<?php

namespace Acme\BudgetTrackerBundle\Controller;
use Acme\BudgetTrackerBundle\Entity\Report;
use Acme\BudgetTrackerBundle\Form\Type\ReportType;

use Acme\BudgetTrackerBundle\Controller\Controller as Controller;

class ReportsController extends Controller
{
    public function reportsAction()
    {
         $this->user = $this->container->get('security.context')->getToken()->getUser();
        $cat_repo = $this->setRepository('Category');
        $all_categories = $cat_repo->findByUser($this->user);
        
        $report = new Report();
        $form = $this->createForm(new ReportType(), $report);
        
        return $this->render('AcmeBudgetTrackerBundle:Reports:reports.html.twig', array(
            'all_categories' => $all_categories,
            'form' => $form->createView()
        ));
    }
}