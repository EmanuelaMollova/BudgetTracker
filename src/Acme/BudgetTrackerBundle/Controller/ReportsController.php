<?php

namespace Acme\BudgetTrackerBundle\Controller;
use Acme\BudgetTrackerBundle\Entity\Report;
use Acme\BudgetTrackerBundle\Form\Type\ReportType;
use Symfony\Component\HttpFoundation\Request;

use Acme\BudgetTrackerBundle\Controller\Controller as Controller;

class ReportsController extends Controller
{
    public function reportsAction(Request $request)
    {   $response = null;
        $first_category = null;
         $this->user = $this->container->get('security.context')->getToken()->getUser();
        //$cat_repo = $this->setRepository('Category');
        //$all_categories = $cat_repo->findByUser($this->user);
        
        $repo = $this->setRepository('Expense');

        $report = new Report();
        $today = new \DateTime();
        $date = $today->format('d-m-Y');
        $report->setEndDate($date);
        $report->setStartDate($date);
        $form = $this->createForm(new ReportType($this->user), $report);
        
        if ($request->isMethod('POST')) {
            $form->bind($request);
        
            $params = $request->request->all();

            $cats = $params['month']['categories'];

            //create query for categories
            $start_date = $params['month']['start_date'];
            $end_date = $params['month']['end_date'];
            
            $start_dateObj = \DateTime::createfromformat('d-m-Y', $start_date);
            $start_dateObj->setTime(0, 0, 0);
            
            $end_dateObj = \DateTime::createfromformat('d-m-Y', $end_date);
            $end_dateObj->setTime(0, 0, 0);
            $end_dateObj->modify('+1 day');
            
            $query = '';
            
            $query .=' AND (e.category='.$cats[0];
            
            array_shift($cats);
            foreach ($cats as $c)
            {
                $query .= ' OR e.category='.$c;
            }
            
            $query .= ')';
            
            $response = $repo->findForCatsAndTimes($this->user, $start_dateObj, $end_dateObj, $query); 
            
            $total_sum = $repo->findSumBetweenDates($this->user, $start_dateObj, $end_dateObj);
            
            if($response){
            $first_category = $response[0]->getCategory()->getName();
            } 
            //$response = $repo->findBetweenDates($this->user, $start_date, $end_date);
            
                    return $this->render('AcmeBudgetTrackerBundle:Reports:take_reports.html.twig', array(
            //'all_categories' => $all_categories,
            //'exp' => $exp,
            'start_date' => $start_dateObj->format('d F Y'),
            'end_date' => $end_dateObj->format('d F Y'),
            'response' => $response,
            'first_category' => $first_category,
            'total_sum' => $total_sum
        ));
        }
        
        return $this->render('AcmeBudgetTrackerBundle:Reports:reports.html.twig', array(
            //'all_categories' => $all_categories,
            //'exp' => $exp,
            'response' => $response,
            'first_category' => $first_category,
            'form' => $form->createView()
        ));
    }
    
    /*public function takeReportAction(Request $request)
    {
        //$this->em = $this->getEM();
        //$this->user = $this->container->get('security.context')->getToken()->getUser();
        
        $report = new Report();
        $form = $this->createForm(new ReportType($this->user), $report);
        
        if ($request->isMethod('POST')) {
            $form->bind($request);
            
            $params = $request->request->all();
            
            $cats = $params['month']['categories'];
            
            //var_dump($cats);
            $query='';
            foreach ($cats as $c)
            {
                $query .= ' AND e.category='.$c;
            }
            
          //  var_dump($query);
            
            $this->user = $this->container->get('security.context')->getToken()->getUser();
        
            $repo = $this->setRepository('Expense');
            
            $response = null;
            
            $response = $repo->findForCat($this->user, $query);
            
            //var_dump($a);
            //die();
        }
//            var_dump($params['month']['start_date']);
//            var_dump($params['month']['end_date']);
           // die();
           
           return $this->render(
            'AcmeBudgetTrackerBundle:Reports:take_reports.html.twig', array(
                'request' => $request,
                'report' => $report,
                'response' => $response,
                'form' => $form->createView()));    
        }
            
//            if ($same == 0 && $form->isValid()) {
//                $month->setUser($this->user);
//                $this->em->persist($month);
//                $this->em->flush();

//                return $this->redirect($this->generateUrl('months'));
//            } else {
//                echo "YOU BAD PERSON!!!";
//            } */
        
}