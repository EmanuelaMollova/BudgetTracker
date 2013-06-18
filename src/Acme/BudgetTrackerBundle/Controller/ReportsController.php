<?php

namespace Acme\BudgetTrackerBundle\Controller;
use Acme\BudgetTrackerBundle\Entity\Report;
use Acme\BudgetTrackerBundle\Form\Type\ReportType;
use Symfony\Component\HttpFoundation\Request;

use Acme\BudgetTrackerBundle\Controller\Controller as Controller;

/*
 * Creates report queries and displays reports
 */
class ReportsController extends Controller
{
    /*
     * Displays the form from which the users query for request.
     * Renders another teplate for the actual report.
     */
    
    //user, expense_repo, category_repo
    public function reportsAction(Request $request)
    {   
        $this->setUser(); 
        $newcommer = false;
        $category_repository = $this->setRepository('Category'); 
        $number_of_categories = $category_repository->countByUser($this->user);
        if($number_of_categories == 0){
            $newcommer = true;
        }
  
        $repo = $this->setRepository('Expense');

        $today = new \DateTime();
        $date = $today->format('d-m-Y');
        $report = new Report();
        $report->setEndDate($date);
        $report->setStartDate($date);
        $form = $this->createForm(new ReportType($this->user), $report);
        
        //If report is requested take parameters and find the needed expenses
        if ($request->isMethod('POST')) {
            $form->bind($request);
        
            $request_parameters = $request->request->all();
            
            if(!array_key_exists('categories', $request_parameters['month'])){
                $this->get('session')->setFlash('notice', 'Please select at least one category!');
                return $this->redirect($this->generateUrl('reports'));
            } else {
                $categories = $request_parameters['month']['categories'];
            }

            //Create query for categories
            $start_date = $request_parameters['month']['start_date'];
            $end_date = $request_parameters['month']['end_date'];
            
            $start_date_obj = \DateTime::createfromformat('d-m-Y', $start_date);
            $start_date_obj->setTime(0, 0, 0);
            
            $end_date_obj = \DateTime::createfromformat('d-m-Y', $end_date);
            $end_date_obj->setTime(0, 0, 0);
            $end_date_obj->modify('+1 day');
            
            if($start_date_obj > $end_date_obj){
                $this->get('session')->setFlash('notice', 'Please select a start date which is before the end date!');
                return $this->redirect($this->generateUrl('reports'));
            }
            
            $query = '';
            
            $query .=' AND (e.category='.$categories[0];
            
            array_shift($categories);
            foreach ($categories as $c) {
                $query .= ' OR e.category='.$c;
            }
            
            $query .= ')';
            
            $expenses = $repo->findByCategoriesAndDates($start_date_obj, $end_date_obj, $query, $this->user); 
            
            $total_sum = 0;
            foreach ($expenses as $exp){
                $total_sum += $exp->getPrice();
            }

            if($expenses){
                $first_category = $expenses[0]->getCategory()->getName();
            } else{
                $first_category = null;
            }
            
            return $this->render('AcmeBudgetTrackerBundle:Reports:take_reports.html.twig', array(
                'start_date' => $start_date_obj->format('d F Y'),
                'end_date' => $end_date_obj->format('d F Y'),
                'expenses' => $expenses,
                'first_category' => $first_category,
                'total_sum' => $total_sum
            ));
        }
        
        //Show the form
        return $this->render('AcmeBudgetTrackerBundle:Reports:reports.html.twig', array(
            'newcommer' => $newcommer,
            'form' => $form->createView()
        ));
    }
}
    
//------------------------------------------------------------------------------
    
//TODO 1. Validation!
//TODO 2. Select all checkboxes by default
//TODO 3. Spline diagrams for days?

/*
public function takeReportAction(Request $request)
{
    $this->em = $this->getEM();
    $this->user = $this->container->get('security.context')->getToken()->getUser();

    $report = new Report();
    $form = $this->createForm(new ReportType($this->user), $report);

    if ($request->isMethod('POST')) {
        $form->bind($request);

        $params = $request->request->all();

        $cats = $params['month']['categories'];

        $query='';
        foreach ($cats as $c)
        {
            $query .= ' AND e.category='.$c;
        }

        $this->user = $this->container->get('security.context')->getToken()->getUser();

        $repo = $this->setRepository('Expense');

        $response = null;

        $response = $repo->findForCat($this->user, $query);
    }
 
        return $this->render(
            'AcmeBudgetTrackerBundle:Reports:take_reports.html.twig', array(
                'request' => $request,
                'report' => $report,
                'response' => $response,
                'form' => $form->createView()));    
    }
    if ($same == 0 && $form->isValid()) {
        $month->setUser($this->user);
        $this->em->persist($month);
        $this->em->flush();

        return $this->redirect($this->generateUrl('months'));
    } else {
        echo "YOU BAD PERSON!!!";
    } 
 */