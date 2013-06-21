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
     * Displays the form from which the users query for report.
     * Renders another teplate for the actual report.
     */
    
    //user, newcommer, expense_repo, category_repo
    public function reportsAction(Request $request)
    {   
        $this->setUser(); 
        $newcommer = false;
        $category_repository = $this->setRepository('Category'); 
        $number_of_categories = $category_repository->countByUser($this->user);
        if($number_of_categories == 0){
            $newcommer = true;
        }
        
        //Create the form for reports
        $today = new \DateTime();
        $date = $today->format('d-m-Y');
        $report = new Report();
        $report->setFromDate($date);
        $report->setToDate($date);
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
            $from_date = $request_parameters['month']['from_date'];
            $to_date = $request_parameters['month']['to_date'];
            
            $from_date_object = \DateTime::createfromformat('d-m-Y', $from_date);
            $from_date_object->setTime(0, 0, 0);
            
            $to_date_object = \DateTime::createfromformat('d-m-Y', $to_date);
            $to_date_object->setTime(0, 0, 0);
            $to_date_object->modify('+1 day');
            
            if($from_date_object > $to_date_object){
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
            
            $this->setDebtsLoansIds();
            $repo = $this->setRepository('Expense');
            
            $expenses = $repo->findByCategoriesAndDates($from_date_object, $to_date_object, $query, $this->user, $this->debts_id); 
            $total_sum = 0;
            
            if($expenses){
                $first_category = $expenses[0]->getCategory()->getName();
                foreach ($expenses as $exp){
                    $total_sum += $exp->getPrice();
                }
            } else{
                $first_category = null;
            }

            return $this->render('AcmeBudgetTrackerBundle:Reports:take_reports.html.twig', array(
                'from_date' => $from_date_object->format('d F Y'),
                'to_date' => $to_date_object->format('d F Y'),
                'expenses' => $expenses,
                'first_category' => $first_category,
                'total_sum' => $total_sum
            ));
        }
        
        //Else show the form
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