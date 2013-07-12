<?php

namespace Acme\BudgetTrackerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Acme\BudgetTrackerBundle\Controller\Controller as Controller;
use Acme\BudgetTrackerBundle\Entity\Report;
use Acme\BudgetTrackerBundle\Form\Type\ReportType;

/*
 * Creates report queries and displays reports
 */
class ReportsController extends Controller
{
    /*
     * Displays the form from which the users query for reports.
     * Renders another teplate for the actual report.
     */
    public function reportsAction(Request $request)
    {   
        $this->setVariables($newcomer = true, $month = false, $em = false);
        
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
                $this->setFlash('Please select at least one category!');
                
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
                $this->setFlash('Please select a start date which is before the end date!');
                
                return $this->redirect($this->generateUrl('reports'));
            }

            $query =' AND (e.category='.$categories[0];
            
            array_shift($categories);
            foreach ($categories as $c) {
                $query .= ' OR e.category='.$c;
            }

            $query .= ')';
            
            $expenses = $this->expense_repository->
                findExpensesByCategoriesAndDates($from_date_object, $to_date_object, $query, $this->user, $this->debts_id); 
            
            $bill_payments = $this->bill_payment_repository->
                findPaymentsBetweenDates($this->user, $from_date_object, $to_date_object);
            
            if($bill_payments){
                $sum_of_payments = $this->bill_payment_repository->
                    findSumOfPaymentsBetweenDates($from_date_object, $to_date_object, $this->user);
            } else {
                $sum_of_payments = 0;
            }
            
            $total_sum = 0;
            
            if($expenses){
                $first_category = $expenses[0]->getCategory()->getName();
                foreach ($expenses as $exp){
                    $total_sum += $exp->getPrice();
                }
                
                if($bill_payments){
                    $total_sum += $sum_of_payments;
                } else {
                    $bill_payments = null;
                }
                
            } else{
                $first_category = null;
            }
           
            return $this->render('AcmeBudgetTrackerBundle:Reports:take_reports.html.twig', array(
                'from_date' => $from_date_object->format('d.m.Y'),
                'to_date' => $to_date_object->format('d.m.Y'),
                'expenses' => $expenses,
                'bill_payments' => $bill_payments,
                'sum_of_payments' => $sum_of_payments,
                'first_category' => $first_category,
                'total_sum' => $total_sum));
        }
        
        //Else show the form
        return $this->render('AcmeBudgetTrackerBundle:Reports:reports.html.twig', array(
            'newcomer' => $this->newcomer,
            'form' => $form->createView()));
    }
}